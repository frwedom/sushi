<?php

namespace App;

use App;

class Users
{

    private $table_name = prefix . 'users';
    private $users_table_name;
    private $users_translations_table;
    public $user;

    public function __construct()
    {
        $this->users_table_name = $this->table_name . "_items";
        $this->users_translations_table = $this->users_table_name . "_translations";

        if (isset($_COOKIE['site_user_token']) && !empty($_COOKIE['site_user_token']) && !$this->checkAuth()) {
            $this->signInByToken($_COOKIE['site_user_token']);
        }

        if ($this->checkAuth()) {

            $this->user = $this->getUserInformation($_SESSION['site_user']);
            $this->user['plan'] = $this->getSubscription();

            if (strtolower($this->user['selected_language']) != strtolower(\App::$language->getCurrentLanguage()) && !$_COOKIE['lang_set']) {
                $languages = \App::$language->getLanguages();

                if ($languages) {
                    foreach ($languages as $language) {
                        if (strtolower($language['code']) == strtolower($this->user['selected_language'])) {
                            $_SESSION['site_lang'] = $language['code'];
                            setcookie('lang_set', true, time() + (86400 * 30), "/");
                            header("Refresh:0");
                            exit();
                        }
                    }
                }
            }

            if ($this->user) {
                $vals['last_visit'] = date("Y-m-d H:i:s");

                $table_info = [
                    'table_name' => $this->users_table_name,
                ];

                \App::$db->update($table_info, $this->user[0], $vals);

                $date_now = date("Y-m-d H:i:s");
                $expiration_date = $this->user['plan']['expiration_date'];

                if (strtotime($date_now) > strtotime($expiration_date)) {
                    $table_name = prefix . 'users_subscription';

                    $sql = "UPDATE " . $table_name . "
                        SET plan_id = :plan_id
                        WHERE user_id = :user_id";

                    $subsValues['user_id'] = $this->user['plan']['user_id'];
                    $subsValues['plan_id'] = 0;

                    \App::$db->executeUpdate($sql, $subsValues);

                }

            } else {
                $this->signOut();
            }


        }
    }

    public function signUp($values)
    {

        $json = json_decode($values);

        $signUpValues = [];
        $response = [];
        $sign_in_values = (object)'';

        $maxId = (int)\App::$db->getMaxId($this->users_table_name) + 1;

        //Валидация
        $validate = $this->validateSignUp($json);

        if (is_array($validate)) {
            $response['errors'] = $validate;
            return $response;
        }
        //Конец валидации

        $signUpValues['connect'] = 1;
        $signUpValues['active'] = 1;
        $signUpValues['confirmed'] = 0;
        $signUpValues['name'] = \App::$functions->check_input($json->name);
        $signUpValues['email'] = \App::$functions->check_input($json->email);

        $signUpValues['birthday'] = \App::$functions->check_input($json->birthday);
        $signUpValues['city_id'] = \App::$functions->check_input($json->city_id);
        $signUpValues['institution_name'] = \App::$functions->check_input($json->institution_name);
        $signUpValues['institution_address'] = \App::$functions->check_input($json->institution_address);
        $signUpValues['iin'] = \App::$functions->check_input($json->iin);

        $signUpValues['password'] = password_hash(\App::$functions->check_input($json->password), PASSWORD_DEFAULT);
        $signUpValues['token'] = bin2hex(random_bytes(64));
        $signUpValues['confirm_token'] = \App::$functions->simpleToken();
        $signUpValues['recover_token'] = \App::$functions->simpleToken();
        $signUpValues['date_added'] = date("Y-m-d H:i:s");
        $signUpValues['date_edited'] = date("Y-m-d H:i:s");
        $signUpValues['unique_id'] = 'id' . rand(10000, 99999) . $maxId;
        $signUpValues['building_type'] = \App::$functions->check_input($json->building_type);

        $table_info = [
            'table_name' => $this->users_table_name,
            'translations_table' => $this->users_translations_table,
        ];

        if (!\App::$db->insert($table_info, $signUpValues)) {
            return $response;
        } else {
            //Входим как пользователь после регистрации
            $sign_in_values->email = $signUpValues['email'];
            $sign_in_values->password = \App::$functions->check_input($json->password);
            $sign_in_values->remember = 1;
            $this->signIn($sign_in_values);
        }

    }

    public function validateSignUp($json)
    {
        $validate = true;
        $response = [];

        if ($this->checkUserExistence(\App::$functions->check_input($json->email))) {
            $response['user_exists'] = true;
            $validate = false;
        }

        if (!$json->email) {
            $response['required_email'] = true;
            $validate = false;
        }

        if (!$json->birthday) {
            $response['required_birthday'] = true;
            $validate = false;
        }

        if (!$json->city_id) {
            $response['required_city_id'] = true;
            $validate = false;
        }

        if (!$json->institution_name) {
            $response['required_institution_name'] = true;
            $validate = false;
        }

        if (!$json->institution_address) {
            $response['required_institution_address'] = true;
            $validate = false;
        }

        if (!$json->iin) {
            $response['required_iin'] = true;
            $validate = false;
        }

        if (!$json->name) {
            $response['required_name'] = true;
            $validate = false;
        }

        if (!filter_var($json->email, FILTER_VALIDATE_EMAIL)) {
            $response['valid_email'] = true;
            $validate = false;
        }

        if (!$json->policy) {
            $response['policy'] = true;
            $validate = false;
        }


        if (!$json->password) {
            $response['password'] = true;
            $validate = false;
        } elseif (strlen($json->password) < 8) {
            $response['password_length'] = true;
            $validate = false;
        }

        if ($validate) {
            return true;
        } else {
            return $response;
        }
    }

    public function signUpBySocial($type, $values)
    {
        if ($type == 'vk') {
            $signUpValues['connect'] = 1;
            $signUpValues['active'] = 1;
            $signUpValues['confirmed'] = 1;
            $signUpValues['vk_register'] = 1;
            $signUpValues['token'] = bin2hex(random_bytes(64));
            $signUpValues['confirm_token'] = \App::$functions->simpleToken();
            $signUpValues['recover_token'] = \App::$functions->simpleToken();
            $signUpValues['password'] = password_hash(\App::$functions->simpleToken(), PASSWORD_DEFAULT);
            $signUpValues['date_added'] = date("Y-m-d H:i:s");
            $signUpValues['date_edited'] = date("Y-m-d H:i:s");
            $signUpValues['unique_id'] = \App::$functions->check_input($values['id']);
            $signUpValues['birthday'] = date("Y-m-d H:i:s", strtotime($values['bdate']));
            $signUpValues['name'] = \App::$functions->check_input($values['last_name']) . ' ' . \App::$functions->check_input($values['first_name']);

            $images[] = $values['photo_big'];
            $signUpValues['images'] = json_encode($images);

            $table_info = [
                'table_name' => $this->users_table_name,
                'translations_table' => $this->users_translations_table,
            ];

            if (\App::$db->insert($table_info, $signUpValues)) {

                $this->signInBySocial($signUpValues);

                return true;
            }

            return false;
        }

    }

    public function checkUserExistence($email)
    {

        $query = [
            'first_table' => $this->users_table_name,
            'second_table' => $this->users_translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["email = :email", true, 'AND']
            ],
            'limit' => '1'
        ];

        $query_params = [
            'email' => $email
        ];

        $user = \App::$db->selectInner($query, true, $query_params);

        if (count($user) > 0) {
            return true;
        }

        return false;
    }

    public function checkUserExistenceById($id)
    {

        $query = [
            'first_table' => $this->users_table_name,
            'second_table' => $this->users_translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["unique_id = :unique_id", true, 'AND']
            ],
            'limit' => '1'
        ];

        $query_params = [
            'unique_id' => $id
        ];

        $user = \App::$db->selectInner($query, true, $query_params);

        if (count($user) > 0) {
            return $user[0];
        }

        return false;
    }

    public function confirmation($values)
    {
        if ($values) {
            $query = [
                'first_table' => $this->users_table_name,
                'second_table' => $this->users_translations_table,
                'join' => [
                    ['id', '=', 'connect']
                ],
                'where' => [
                    ["id = :id", true, 'AND']
                ],
                'limit' => '1'
            ];

            $query_params = [
                'id' => $values['id']
            ];

            $user = \App::$db->selectInner($query, true, $query_params);

            if (!$user['confirmed'] && $user['confirm_token'] == $values['confirm_token']) {
                $vals['confirmed'] = 1;
                $vals['confirm_token'] = null;

                $table_info = [
                    'table_name' => $this->users_table_name,
                ];

                \App::$db->update($table_info, $values['id'], $vals);

                return true;
            }

            return false;

        }
    }

    public function signIn($values)
    {

        if (!is_object($values)) {
            $json = json_decode($values);
        } else {
            $json = $values;
        }

        $email = $json->email;
        $password = \App::$functions->check_input($json->password);
        $remember = \App::$functions->check_input($json->remember);

        $response = array(
            'login' => false,
            'password' => false,
            'active' => false,
            'status' => false,
        );

        if (!$email) {
            $response['errors']['empty_email'] = true;
        }

        if (!$password) {
            $response['errors']['empty_password'] = true;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['wrong_email'] = true;
        }


        $query = [
            'first_table' => $this->users_table_name,
            'second_table' => $this->users_translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["email = :email", true, 'AND']
            ],
            'limit' => '1'
        ];

        $query_params = [
            'email' => $email
        ];

        $user = \App::$db->selectInner($query, true, $query_params);

        if (count($user) == 1) {
            $response['login'] = true;

            if ($user[0]['active'] == 1) {
                $response['active'] = true;

                if (password_verify($password, $user[0]['password'])) {
                    $response['password'] = true;

                    $this->user = $user[0];
                    $this->user['plan'] = $this->getSubscription();

                    $_SESSION['site_user'] = $this->user[0];
                    if ($remember == 1) {

                        setcookie('site_user_token', $this->user['token'], time() + (86400 * 30), "/");

                    }

                    $response['status'] = true;
                } else {
                    $response['errors']['false_password'] = true;
                }

            } else {
                $response['errors']['active'] = true;
            }

        } else {
            $response['errors']['no_result'] = true;
        }

        return $response;

    }

    public function signInBySocial($values)
    {

        $response = [];

        if ($values['active'] == 1) {
            $response['active'] = true;

            $this->user = $values;

            $_SESSION['site_user'] = $this->user[0];

            setcookie('site_user_token', $this->user['token'], time() + (86400 * 30), "/");

            $response['status'] = true;


        } else {
            $response['errors']['active'] = true;
        }

        return $response;
    }


    public function signInByToken($token)
    {
        $token = \App::$functions->check_input($token);

        // Находим пользователя
        $query = [
            'first_table' => $this->users_table_name,
            'second_table' => $this->users_translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["token = :token", true, 'AND']
            ],
            'limit' => '1'
        ];

        $query_params = [
            'token' => $token
        ];

        $user = \App::$db->selectInner($query, true, $query_params);

        if (count($user) == 1) {
            $_SESSION['site_user'] = $user[0][0];
        }
    }

    public function recover($values)
    {

        $json = $values;

        $validate = true;
        $response = [];

        if (!$this->checkUserExistence(\App::$functions->check_input($json->email))) {
            $response['errors']['user_non_exists'] = true;
            $validate = false;
        }

        if (!$json->email) {
            $response['errors']['required_email'] = true;
            $validate = false;
        }

        if (!filter_var($json->email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['valid_email'] = true;
            $validate = false;
        }

        if ($validate) {
            return true;
        } else {
            return $response;
        }
    }

    public function checkRecoverToken($values)
    {

        $response = false;

        if ($values['id'] && $values['token']) {
            $user = $this->getUserInformation($values['id']);

            if ($user['recover_token'] == $values['token']) {

                setcookie('user_recover_id', $user[0], time() + (1200), "/");

                $response = true;

                $vals['recover_token'] = \App::$functions->simpleToken();

                $table_info = [
                    'table_name' => $this->users_table_name,
                ];

                \App::$db->update($table_info, $user[0], $vals);
            }
        }

        return $response;
    }

    public function recoverIt($values)
    {
        if ($_COOKIE['user_recover_id']) {
            $validate = true;
            $response = [];

            if (!$values->password) {
                $response['errors']['password'] = true;
                $validate = false;
            } elseif (strlen($values->password) < 8) {
                $response['errors']['password_length'] = true;
                $validate = false;
            }

            if ($values->password !== $values->re_password) {
                $response['errors']['re_password'] = true;
                $validate = false;
            }

            if ($validate) {
                $vals['password'] = password_hash(\App::$functions->check_input($values->password), PASSWORD_DEFAULT);

                $table_info = [
                    'table_name' => $this->users_table_name,
                ];

                \App::$db->update($table_info, $_COOKIE['user_recover_id'], $vals);

                return true;
            } else {
                return $response;
            }
        }
    }

    public function editProfile($values)
    {

        $id = $this->user[0];

        $form_values['name'] = \App::$functions->check_input($values->profile->name);
        $form_values['email'] = \App::$functions->check_input($values->profile->email);
        $form_values['birthday'] = \App::$functions->check_input($values->profile->birthday);
        $form_values['city_id'] = \App::$functions->check_input($values->profile->city_id);
        $form_values['institution_name'] = \App::$functions->check_input($values->profile->institution_name);
        $form_values['institution_address'] = \App::$functions->check_input($values->profile->institution_address);
        $form_values['iin'] = \App::$functions->check_input($values->profile->iin);

        $form_values['date_edited'] = date('Y-m-d H:i:s');

        $validate = true;
        $response = [];

        if ($this->checkUserExistence(\App::$functions->check_input($values->profile->email)) && $this->user['email'] !== $values->profile->email) {
            $response['user_exists'] = true;
            $validate = false;
        }

        if (!$values->profile->email) {
            $response['required_email'] = true;
            $validate = false;
        }

        if (!filter_var($values->profile->email, FILTER_VALIDATE_EMAIL)) {
            $response['valid_email'] = true;
            $validate = false;
        }

        if (!$values->profile->birthday) {
            $response['required_birthday'] = true;
            $validate = false;
        }

        if (!$values->profile->city_id) {
            $response['required_city_id'] = true;
            $validate = false;
        }

        if (!$values->profile->institution_name) {
            $response['required_institution_name'] = true;
            $validate = false;
        }

        if (!$values->profile->institution_address) {
            $response['required_institution_address'] = true;
            $validate = false;
        }

        if (!$values->profile->iin) {
            $response['required_iin'] = true;
            $validate = false;
        }

        if (!$values->profile->name) {
            $response['required_name'] = true;
            $validate = false;
        }

        if ($validate) {

            $table_info = [
                'table_name' => $this->users_table_name,
            ];

            \App::$db->update($table_info, $id, $form_values);

            return true;
        } else {
            $response['errors'] = $response;

            return $response;
        }

    }

    public function changePassword($values)
    {

        $id = $this->user[0];

        $validate = true;
        $response = [];

        if (!$values->current_password) {
            $response['current_password'] = true;
            $validate = false;
        }

        $verify = password_verify($values->current_password, $this->user['password']);

        if (!$verify) {
            $response['verify'] = true;
            $validate = false;
        }


        if (!$values->password) {
            $response['password'] = true;
            $validate = false;
        } elseif (strlen($values->password) < 8) {
            $response['password_length'] = true;
            $validate = false;
        }

        if ($values->password !== $values->re_password) {
            $response['re_password'] = true;
            $validate = false;
        }

        if ($validate) {

            $form_values['password'] = password_hash(\App::$functions->check_input($values->password), PASSWORD_DEFAULT);

            $table_info = [
                'table_name' => $this->users_table_name,
            ];

            \App::$db->update($table_info, $id, $form_values);

            return true;
        } else {
            $response['errors'] = $response;

            return $response;
        }

    }

    public function getSubscription()
    {

        $query = [
            'table' => prefix . 'users_subscription',
            'where' => [
                ["user_id = :user_id", 'OR'],
                ["user_email = :user_email", 'OR'],
            ],
            'limit' => '1'
        ];

        $query_params = [
            'user_id' => $this->user[0],
            'user_email' => $this->user['email'],
        ];

        $item = \App::$db->select($query, $query_params);

        return $item[0];

    }

    public function changeEmail($values)
    {

        $id = $this->user[0];

        $validate = true;
        $response = [];

        if ($this->checkUserExistence(\App::$functions->check_input($values->email))) {
            $response['email_exists'] = true;
            $validate = false;
        }

        if (!$values->email) {
            $response['email'] = true;
            $validate = false;
        }

        if (!filter_var($values->email, FILTER_VALIDATE_EMAIL)) {
            $response['valid_email'] = true;
            $validate = false;
        }

        if ($values->email == $this->user['email']) {
            $response['equal'] = true;
            $validate = false;
        }


        if ($validate) {

            $form_values['name'] = $values->email;
            $form_values['email'] = $values->email;

            $table_info = [
                'table_name' => $this->users_table_name,
            ];

            \App::$db->update($table_info, $id, $form_values);

            return true;
        } else {
            $response['errors'] = $response;

            return $response;
        }

    }


    public function checkUserAuth()
    {
        if (!empty($_SESSION['site_user']) && $this->user[0] == $_SESSION['site_user']) {
            return true;
        }
        return false;
    }

    public function checkAuth()
    {
        if (!empty($_SESSION['site_user'])) {
            return true;
        }
        return false;
    }

    public function getUserInformation($id)
    {
        if ($id) {

            // Находим пользователя
            $query = [
                'first_table' => $this->users_table_name,
                'second_table' => $this->users_translations_table,
                'join' => [
                    ['id', '=', 'connect']
                ],
                'where' => [
                    ["id = :id", true, 'AND']
                ],
                'limit' => '1'
            ];
            $query_params = [
                'id' => $id,
            ];

            $user = \App::$db->selectInner($query, true, $query_params);

            if (count($user) == 1) {
                return $user[0];
            }

            return false;
        }
    }

    public function getUserByUniqueId($id)
    {
        if ($id) {

            // Находим пользователя
            $query = [
                'first_table' => $this->users_table_name,
                'second_table' => $this->users_translations_table,
                'join' => [
                    ['id', '=', 'connect']
                ],
                'where' => [
                    ["unique_id = :unique_id", true, 'AND']
                ],
                'limit' => '1'
            ];
            $query_params = [
                'unique_id' => $id,
            ];

            $user = \App::$db->selectInner($query, true, $query_params);

            if (count($user) == 1) {
                return $user[0];
            }

            return false;
        }
    }

    public function getUserByEmail($email)
    {
        if ($email) {

            // Находим пользователя
            $query = [
                'first_table' => $this->users_table_name,
                'second_table' => $this->users_translations_table,
                'join' => [
                    ['id', '=', 'connect']
                ],
                'where' => [
                    ["email = :email", true, 'AND']
                ],
                'limit' => '1'
            ];
            $query_params = [
                'email' => $email,
            ];

            $user = \App::$db->selectInner($query, true, $query_params);

            if (count($user) == 1) {
                return $user[0];
            }

            return false;
        }
    }

    public function signOut()
    {
        unset($_SESSION['site_user']);
        setcookie('site_user_token', '', time() + (0), "/");
        return true;
    }

    public function remove($ids = array())
    {

        if (empty($ids)) {
            $ids[] = $this->user[0];
        }

        $table_info = [
            'table_name' => $this->users_table_name,
            'translations_table' => $this->users_translations_table,
            'type' => 1
        ];

        if (\App::$db->remove($table_info, $ids)) {
            return true;
        }

        return false;
    }

    public function getUserId()
    {
        return (int)$_SESSION['site_user'];
    }

}