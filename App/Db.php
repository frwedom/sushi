<?php

namespace App;

use App;

class Db
{

    public $pdo;

    /**
     * Db constructor.
     * Получаем данные входа и подключаемся к БД
     */
    public function __construct()
    {

        try {
            $settings = $this->getPDOSettings();
            $this->pdo = new \PDO($settings['dsn'], $settings['user'], $settings['pass'], null);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Ошибка подключения к базе данных: " . $e->getMessage();
            die();
        }

    }

    /**
     * Загружаем данные для входа в БД
     * @return array
     */
    protected function getPDOSettings()
    {

        $config = include $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Db.php';
        $result['dsn'] = "{$config['type']}:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $result['user'] = $config['user'];
        $result['pass'] = $config['pass'];
        return $result;
    }

    /**
     * Принимает любой sql запрос
     * @param string $query sql запрос
     * @param array $params параметры для запроса
     * @return array
     */
    public function execute($query, array $params = null)
    {

        if (is_null($params)) {
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll();
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $params[$key] = \App::$functions->check_input($value);
            }
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();

    }

    public function executeUpdate($query, array $params = null) {

        try {
            $stmt = $this->pdo->prepare($query);

            if (is_array($params)) {
                foreach ($params as $k => $v) {
                    $params[$k] = \App::$functions->check_input($v);
                }
            }

            $stmt->execute($params);

            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage() . "<br><br><small> Query: " . $query . "</small>";
            return false;
        }

    }

    /**
     * Обычная выборка
     * @param array $query собираем sql запрос с массива
     * @param array $params параметры для запроса
     * @return array
     */
    public function select($query, array $params = null)
    {
        if (is_array($query)) {
            //Собираем запрос
            $select = $query['select'];
            $table = $query['table'];
            $where = $query['where'];
            $order = $query['order'];
            $limit = $query['limit'];

            //SELECT
            if (!$select) {
                $select = "*";
            }

            //WHERE
            if (is_array($where)) {
                $where_to_sql = '';

                foreach ($where as $k => $v) {
                    $where_values[] = $v;

                    if (!key_exists($k + 1, $where)) {
                        $v[1] = '';
                    } else {
                        $v[1] = " " . $v[1] . " ";
                    }

                    $where_to_sql .= $v[0] . $v[1];
                }

                $where = 'WHERE ' . \App::$functions->check_input($where_to_sql);
            }

            //ORDER
            if (is_array($order)) {
                foreach ($order as $k => $v) {
                    $order_values[] = $k . " " . $v;
                }
                $order_values = implode(', ', $order_values);
                $order = 'ORDER BY ' . $order_values;
            }

            //LIMIT
            if ($limit && is_string($limit)) {
                $limit = "LIMIT " . $limit;
            }

            //Запрос
            $sql = "SELECT {$select} FROM {$table} {$where} {$order} {$limit}";

//            \App::$functions->dumper($sql);

            if (is_null($params)) {
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll();
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();

        }

    }

    /**
     * Выборка с join'ом (Используется в основном для вывода переводов)
     * @param array $query собираем sql запрос с массива
     * @param bool $language собираем sql запрос с массива
     * @param array $params параметры для запроса
     * @return array
     */
    public function selectInner($query, $language = false, array $params = null, $debug_mode = false)
    {

        if (is_array($query)) {

            //Принимаем названия таблиц
            $first_table = $query['first_table'];
            $second_table = $query['second_table'];

            //Собираем запрос
            $select = $query['select'];
            $join = $query['join'];
            $where = $query['where'];
            $order = $query['order'];
            $limit = $query['limit'];

            //SELECT
            if (!$select) {
                $select = "*";
            } elseif (is_array($select)) {

                foreach ($select as $k => $v) {

                    if ($v[1] == true) {
                        $select_table = $first_table;
                    } else {
                        $select_table = $second_table;
                    }
                    $select_values[] = "{$select_table}." . $v[0];

                }

                $select_sql = implode(', ', $select_values);

                $select = "{$select_sql}";
            }

            //JOIN
            if (is_array($join)) {
                foreach ($join as $v) {
                    $join_values[] = $first_table . "." . $v[0] . $v[1] . $second_table . "." . $v[2];
                }

                $join_values = implode(' AND ', $join_values);

                $join = $join_values;

                if ($language) {
                    $join .= " AND {$second_table}.language_code = '" . \App::$language->getCurrentLanguage() . "'";
                }

            }

            //WHERE
            if (is_array($where)) {
                $where_to_sql = '';

                foreach ($where as $k => $v) {
                    if ($v[1] == true) {
                        $where_table = $first_table;
                    } else {
                        $where_table = $second_table;
                    }
                    $where_values[] = $where_table . "." . $v[0];

                    if (!key_exists($k + 1, $where)) {
                        $v[2] = '';
                    } else {
                        $v[2] = " " . $v[2] . " ";
                    }

                    $where_to_sql .= $where_table . "." . $v[0] . $v[2];
                }

                $where = 'WHERE ' . $where_to_sql;
            }

            //ORDER
            if (is_array($order)) {
                foreach ($order as $k => $v) {

                    if ($v[1] == true) {
                        $order_table = $first_table;
                    } else {
                        $order_table = $second_table;
                    }
                    $order_values[] = $order_table.'.'.$v[0];
                }
                $order_values = implode(', ', $order_values);

                $order = "ORDER BY {$order_values}";
            }

            //LIMIT
            if ($limit && is_string($limit)) {
                $limit = "LIMIT " . $limit;
            }

            //Запрос
            $sql = "SELECT {$select}  FROM {$first_table} LEFT JOIN {$second_table} ON {$join} {$where} {$order} {$limit}";

            if ($debug_mode) {
                echo $sql;
            }

            //Если параметров нету
            if (is_null($params)) {
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll();
            }

            //Если есть параметры
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();

        }

    }

    /**
     * Вставка элементов + Вставляем переводы
     * @param array $table_info Информация о таблице для вставки
     * @param array $values собираем sql запрос с массива
     * @return bool
     */
    public function insert($table_info, $values)
    {
        $table_name = $table_info['table_name'];
        $translations_table = $table_info['translations_table'];
        $type = $table_info['type'];

        $cols = [];
        $vals = [];
        $cols_name = [];

        $translate_cols = [];
        $translate_vals = [];
        $translate_cols_name = [];

        foreach ($values as $k => $v) {
            //Если это обычные данные
            if (!is_array($v)) {
                $cols[] = \App::$functions->check_input($k);
                $vals[$k] = $v;

                $cols_name[] = ':' . \App::$functions->check_input($k);
            }

            //Если массив
            if (is_array($v) && $k !== 'translate' && count($v) > 0) {
                $cols[] = ($k);
                $image_vals = [];
                foreach ($v as $image_v) {
                    $image_vals[] = $image_v;
                }
                $vals[$k] = json_encode($image_vals);

                $cols_name[] = ':' . $k;
            }

            //То что переводится
            if (is_array($v) && $k === 'translate' && count($v) > 0) {
                foreach ($v as $translate_k => $translate_v) {
                    $translate_cols[] = ($translate_k);
                    $translate_vals[$translate_k] = ($translate_v);

                    $translate_cols_name[] = ':' . ($translate_k);
                }
            }
        }

        //Обычные данные
        $columns = implode(', ', $cols);
        $values = $vals;
        $columns_name = implode(', ', $cols_name);

        //Вставляем в главную таблицу
        try {
            $sql = "INSERT INTO {$table_name} ({$columns}) VALUES ({$columns_name})";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
            return false;
        }

        //Вставляем переводы
        if ($translations_table) {
            $languages = \App::$language->getLanguages();

            //Максимальный Id текущей таблицы
            $maxId = $this->getMaxId($table_name);

            //Связь перевода с элементом
            $translate_cols['connect'] = 'connect';
            $translate_vals['connect'] = $maxId;
            $translate_cols_name['connect'] = ':connect';

            //Тип - Раздел или элемент
            $translate_cols['type'] = 'type';
            $translate_vals['type'] = $type;
            $translate_cols_name['type'] = ':type';

            //Вставляем каждый язык
            foreach ($languages as $v) {
                $translate_cols['language_code'] = 'language_code';
                $translate_vals['language_code'] = $v['code'];
                $translate_cols_name['language_code'] = ':language_code';

                //Собираем переводы в строку
                $translate_columns = implode(', ', $translate_cols);
                $translate_columns_name = implode(', ', $translate_cols_name);

                //Вставляем перевод
                try {
                    $sql = "INSERT INTO $translations_table ({$translate_columns}) VALUES ({$translate_columns_name})";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($translate_vals);
                } catch (\PDOException $e) {
                    echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Обновление элемента
     * @param array $table_info Информация о таблице
     * @param int $id ID Элемента
     * @param array $values Значения
     * @return bool
     */
    public function update($table_info, $id, $values)
    {

        $table_name = $table_info['table_name'];
        $translations_table = $table_info['translations_table'];
        $type = $table_info['type'];

        $cols = [];
        $vals = [];

        $translate_cols = [];
        $translate_vals = [];

        //Принимаем только url основного языка
        if ($values['url'] && \App::$language->getCurrentLanguage() !== \App::$language->getDefaultLanguage()['code']) {
            unset($values['url']);
        }

        foreach ($values as $k => $v) {

            if (!is_array($v)) {
                $cols[] = ($k) . "=:" . ($k);
                $vals[$k] = ($v);
            }

            //Если картинки
            if (is_array($v) && $k !== 'translate' && count($v) > 0) {
                $cols[] = ($k) . "=:" . ($k);
                foreach ($v as $image_v) {
                    $image_vals[] = ($image_v);
                }
                $vals[$k] = json_encode($image_vals);
            }

            //То что переводится
            if (is_array($v) && $k === 'translate' && count($v) > 0) {
                foreach ($v as $translate_k => $translate_v) {

                    $translate_cols[] = ($translate_k) . "=:" . ($translate_k);
                    $translate_vals[$translate_k] = ($translate_v);
                }
            }
        }

        //Обычные данные
        $columns = implode(', ', $cols);
        $values = $vals;

        //То что переводится
        $translate_columns = implode(', ', $translate_cols);
        $translate_values = $translate_vals;

        //Обновляем главную таблицу
        try {
            $sql = "UPDATE {$table_name} SET {$columns} WHERE id = {$id}";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
            print_r($values);
            return false;
        }


        if ($translations_table) {
            //Если нужно обновить все языковые версии
            if ($values['update_all_languages']) {

                $languages = \App::$language->getLanguages();

                foreach ($languages as $v) {

                    try {
                        $sql = "UPDATE $translations_table SET {$translate_columns} WHERE connect = {$id} AND language_code = '" . $v['code'] . "'";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute($translate_values);
                    } catch (\PDOException $e) {
                        echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
                        return false;
                    }
                }

            } else {

                //Обновляем только текущий язык
                try {
                    $sql = "UPDATE $translations_table SET {$translate_columns} WHERE connect = {$id} AND language_code = '" . \App::$language->getCurrentLanguage() . "'";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($translate_values);

                } catch (\PDOException $e) {
                    echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Удаляем элементы
     * @param array $table_info Информация о таблице
     * @param array $ids ID элементов в массиве списке
     * @return bool
     */
    public function remove($table_info, $ids)
    {
        $table_name = $table_info['table_name'];
        $translations_table = $table_info['translations_table'];
        $type = $table_info['type'];

        //Удаляем с основной таблицы
        $this->mainTableRemove($table_name, $ids);

        //Удаляем с таблицы переводов
        $this->translationTableRemove($translations_table, $ids, $type);

        return true;

    }

    public function mainTableRemove($table_name, $ids)
    {
        foreach ($ids as $v) {
            try {
                $sql = "DELETE FROM {$table_name} WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array(
                    'id' => $v
                ));
            } catch (\PDOException $e) {
                echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
                return false;
            }
        }
    }

    public function translationTableRemove($table_name, $ids, $type)
    {
        foreach ($ids as $v) {
            try {
                $sql = "DELETE FROM {$table_name} WHERE connect = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array(
                    'id' => $v,
                ));
            } catch (\PDOException $e) {
                echo $e->getMessage() . "<br><br><small> Query: " . $sql . "</small>";
                return false;
            }
        }
    }

    /**
     * Получаем количество элементов в таблице где connect = $connect
     * @param string $table_name Имя таблицы
     * @return int
     */
    public function connect_count($table_name, $connect = "")
    {
        if (!is_null($connect)) {
            $connect = 'WHERE connect = ' . $connect;
        }
        $sql = "SELECT COUNT(*) FROM {$table_name} $connect";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $number_of_rows = $stmt->fetchColumn();

        return $number_of_rows;
    }

    /**
     * Получаем максимальный ID
     * @param string $table_name Имя таблицы
     * @return int
     */
    public function getMaxId($table_name)
    {
        $sql = "SELECT id from {$table_name} ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $max_id = $stmt->fetchColumn();

        return $max_id;
    }
}
