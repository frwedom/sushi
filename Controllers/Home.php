<?php

namespace Controllers;

class Home extends \App\Controller
{


    //Параметры пагинации
    public $per_page = 16;
    public $pagination_start = 0;
    public $current_pagination = 0;
    public $pagination_params;

    public function __construct()
    {
        define('HOME', '');

        //Загружаем переводы для статичных текстов
        $this->translates = $this->getTranslates(strtolower(\App::$language->getCurrentLanguage()));
    }

    public function index ()
    {

        $article = $this->getArticleParams('main');
        $this->setSEOParams($article);

        $query = [
            'first_table' => prefix . 'main',
            'second_table' => prefix . 'main_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["id = :id", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'id' => 1,
        ];

        $params['banner'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'main',
            'second_table' => prefix . 'main_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["id = :id", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'id' => 3,
        ];

        $params['dop_block'] = \App::$db->selectInner($query, true, $query_params)[0];

        return $this->render('Home', $params);
        
    }

    public function getAllItems($params = '', $values = '')
    {

        if ($values['get'] == 'events') {
            //Параметры для вывода
            $json = json_decode($values['params']);

            if ($json->page) {
                $this->pagination_start = $json->page;
            }

            if ($json->per_page) {
                $this->per_page = $json->per_page;
            }

            //Основной запрос
            $query = [
                'first_table' => prefix . 'events',
                'second_table' => prefix . 'events_translations',
                'join' => [
                    ['id', '=', 'connect'],
                ],
                'where' => [
                    ["active = :active", true, 'AND'],
                ],
                'order' => [
                    ['date_added DESC', true]
                ],
                'limit' => "{$this->pagination_start}, {$this->per_page}"
            ];

            $query_params = [
                'active' => 1
            ];

            if ($json->city) {

                $query['where'][] = ['city_id = :city_id', true, 'AND'];

                $query_params['city_id'] = (int)\App::$functions->check_input($json->city);

            }

            if ($json->user_id) {

                $query['where'][] = ['user_id = :user_id', true, 'AND'];

                $query_params['user_id'] = (int)\App::$functions->check_input($json->user_id);

            }

            if ($json->range_1) {

                $date = (int)\App::$functions->check_input($json->range_1) / 1000;

                $date = date('Y-m-d H:i:s', $date);

                $query['where'][] = ["event_date >= :range_1", true, 'AND'];
                $query_params['range_1'] = $date;

            }

            if ($json->range_2) {

                $date = (int)\App::$functions->check_input($json->range_2) / 1000;

                $date = date('Y-m-d H:i:s', $date);

                $query['where'][] = ["event_date <= :range_2", true, 'AND'];
                $query_params['range_2'] = $date;

            }

            $items = \App::$db->selectInner($query, true, $query_params);

            for ($i = 0; $i < count($items); $i++) {

                if ($items[$i]['event_date']) {
                    $items[$i]['event_date'] = \App::$functions->timestampToString($items[$i]['event_date']);
                }

            }

            $data['items'] = $items;
            $data['pagination'] = \App::$pagination->getPagination($this->per_page, $this->current_pagination, $query, $query_params);

            $query['select'][] = ['id', true];
            $query['limit'] = ($this->pagination_start + $this->per_page) . ", {$this->per_page}";
            $next_items = \App::$db->selectInner($query, true, $query_params);

            $count = count($next_items);
            if ($count > $this->per_page) $count = $this->per_page;
            $data['next_items'] = $count;
            $data['query'] = $query;
            $data['query_params'] = $query_params;

            return json_encode($data);
        }

        return false;

    }

    public function contactForm($params, $values)
    {

        if ($values['q'] == 'contactForm') {

            $json = [];
            $json['status'] = true;

            $response = $this->contactFormValidate($values['params']);

            if ($response['errors']['required_email']) {
                $json['errors']['email'] = 'Заполните данное поле';
                $json['status'] = false;
            } elseif ($response['errors']['valid_email']) {
                $json['errors']['email'] = 'Неправильный формат почты';
                $json['status'] = false;
            } else {
                $json['errors']['email'] = '';
            }

            if ($response['errors']['required_name']) {
                $json['errors']['name'] = 'Заполните данное поле';
                $json['status'] = false;
            } else {
                $json['errors']['name'] = '';
            }

            if ($response['errors']['required_phone_number']) {
                $json['errors']['phone_number'] = 'Заполните данное поле';
                $json['status'] = false;
            } else {
                $json['errors']['phone_number'] = '';
            }

            if ($response['errors']['required_message']) {
                $json['errors']['message'] = 'Заполните данное поле';
                $json['status'] = false;
            } else {
                $json['errors']['message'] = '';
            }

            if ($json['status']) {

                $json['msg'] = 'Ваше сообщение отправлено. Скоро с вами свяжется наш менеджер!';

            }

            return json_encode($json);
        }

        return false;

    }

    public function contactFormValidate($values)
    {

        $json = json_decode($values);

        $validate = true;
        $response = [];

        if (!$json->email) {
            $response['errors']['required_email'] = true;
            $validate = false;
        }

        if (!filter_var($json->email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['valid_email'] = true;
            $validate = false;
        }

        if (!$json->name) {
            $response['errors']['required_name'] = true;
            $validate = false;
        }

        if (!$json->message) {
            $response['errors']['required_message'] = true;
            $validate = false;
        }

        if (!$json->phone_number) {
            $response['errors']['required_phone_number'] = true;
            $validate = false;
        }

        if ($validate) {

            $headers = \App::$mail->getHeaders();

            $title = 'Сообщение с сайта ';
            $text = "
                <p><b>Имя: </b> " . $json->name .  "</p>
                <p><b>Почта: </b> " . $json->email .  "</p>
                <p><b>Номер телефона: </b> " . $json->phone_number .  "</p>
                <p><b>Сообщение: </b> " . $json->message .  "</p>
            ";

            //Get message layout with title and text
            $message = \App::$mail->messageLayout($title, $text);

            foreach ($this->mail_to as $mail) {
                $send = mail($mail, $title . $_SERVER['HTTP_HOST'], $message, $headers);
            }

            return true;
        } else {
            return $response;
        }

    }


}