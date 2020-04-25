<?php

namespace Controllers;

class Foods extends \App\Controller
{

    public $translates;

    public $controller = 'foods';
    public $translatesFile = 'foods';

    //Имя главной таблицы и таблицы с переводами
    public $table_name;
    public $translations_table;

    public $items_table_name;
    public $items_translations_table_name;

    //Параметры пагинации
    public $per_page = 12;
    public $pagination_start = 0;
    public $current_pagination = 0;
    public $pagination_params;

    public function __construct()
    {

        $this->header_grey = true;

        $this->table_name = prefix . $this->controller;
        $this->translations_table = $this->table_name . '_translations';

        $this->items_table_name = prefix . $this->controller . '_items';
        $this->items_translations_table_name = $this->items_table_name . '_translations';

    }

    public function index()
    {



        //Загржаем сео данные с артиклей
        $article = $this->getArticleParams($this->controller);
        $this->setSeoParams($article);

        //Параметры хлебных крошек
        $breadcrumb_params = [
            'controller' => $this->controller,
            'type' => 0,
        ];

        //Параметры элементов
        $params = [
            'breadcrumb_params' => $breadcrumb_params,
            'categories' => $this->getCategories(),
        ];

        return $this->render('Foods', $params);

    }

    public function getCategories()
    {
        $query = [
            'first_table' => $this->table_name . '',
            'second_table' => $this->table_name . '_translations',
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
            ],
            'order' => [
                ['id DESC', true]
            ],
        ];

        $items = \App::$db->selectInner($query, true);

        return $items;
    }

    public function getCategoryElements($id)
    {
        $query = [
            'select' => [
                ['id', true],
                ['connect', true],
                ['active', true],
            ],
            'first_table' => $this->table_name . '_items',
            'second_table' => $this->table_name . '_items_translations',
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['id DESC', true]
            ],
        ];

        $query_params = [
            'connect' => $id
        ];

        $items = \App::$db->selectInner($query, true, $query_params);

        return count($items);
    }

    public function getCategory($url)
    {
        $query = [
            'first_table' => $this->table_name,
            'second_table' => $this->table_name . '_translations',
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["url = :url", true, 'AND'],
            ],
            'order' => [
                ['id DESC', true]
            ],
        ];

        $query_params = [
            'url' => $url,
        ];
        $item = \App::$db->selectInner($query, true, $query_params);

        return $item[0];
    }

    public function c($params, $values)
    {

        //Получаем url
        $url = $params[0];

        $item = $this->getCategory($url);

        if (empty($item)) {
            throw new \App\Exceptions\InvalidRouteException();
        }

        //Загружаем SEO данные
        $this->setSeoParams($item);

        //Параметры хлебных крошек
        $breadcrumb_params = [
            'controller' => $this->controller,
            'type' => 0,
        ];

        //Параметры элементов
        $params = [
            'breadcrumb_params' => $breadcrumb_params,
            'item' => $item,
            'categories' => $this->getCategories(),
        ];

        return $this->render('Foods', $params);

    }


    public function a($params)
    {

        define('PAGE', '');

        //Получаем url
        $url = $params[0];

        $item = $this->getItem($url);

        if (empty($item)) {
            throw new \App\Exceptions\InvalidRouteException();
        }

        //Загружаем SEO данные
        $this->setSeoParams($item);

        //Параметры хлебных крошек
        $breadcrumb_params = [
            'controller' => $this->controller,
            'id' => $item[0],
            'type' => 1,
        ];

        //Параметры к выводу страницы
        $params = [
            'item' => $item,
            'breadcrumb_params' => $breadcrumb_params,
            'categories' => $this->getCategories(),
        ];

        return $this->render('Foods-single', $params);
    }

    public function getItem($url)
    {
        $query = [
            'first_table' => $this->table_name . '_items',
            'second_table' => $this->table_name . '_items_translations',
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["url = :url", true, 'AND'],
            ],
            'order' => [
                ['id DESC', true]
            ],
        ];

        $query_params = [
            'url' => $url,
        ];
        $item = \App::$db->selectInner($query, true, $query_params);

        return $item[0];
    }

    public function getAllItems($params = '', $values = '')
    {

        if ($values['get'] == 'foods') {
            //Параметры для вывода
            $json = json_decode($values['params']);

            if ($json->page) {
                $this->pagination_start = $json->page;
            }

            //Основной запрос
            $query = [
                'first_table' => $this->items_table_name,
                'second_table' => $this->items_translations_table_name,
                'join' => [
                    ['id', '=', 'connect'],
                ],
                'where' => [
                    ["active = 1", true, 'AND'],
                ],
                'limit' => "{$this->pagination_start}, {$this->per_page}"
            ];

            $query_params = [];

            //Сортировка / Поиск начало
            if ($json->sort_by_value->column) {

                $column = $json->sort_by_value->column;
                $row = $json->sort_by_value->row;

                if ($json->sort_by_value->type == '0') {
                    $type = false;
                } else {
                    $type = true;
                }

                $query['order'][] = [$column . ' ' . $row, $type];

            }

            //Поиск по наименовнию
            if ($json->filter_name) {
                $json->filter_name = \App::$functions->check_input($json->filter_name);
                $name = "%$json->filter_name%";
                $query['where'][] = ['name LIKE :name', false, 'AND'];
                $query_params['name'] = $name;
            }

            //Поиск по наименовнию
            if ($json->connect) {
                $json->connect = \App::$functions->check_input($json->connect);
                $query['where'][] = ['connect = :connect', true, 'AND'];
                $query_params['connect'] = $json->connect;
            }

            //Сортировка / Поиск конец

            $items = \App::$db->selectInner($query, true, $query_params);

            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['date_added']) {
                    $items[$i]['date_added'] = \App::$functions->timestampToString($items[$i]['date_added']);
                }
            }

            $data['items'] = $items;
            $data['pagination'] = \App::$pagination->getPagination($this->per_page, $this->pagination_start, $query, $query_params);

            return json_encode($data);
        }

        return false;

    }

}