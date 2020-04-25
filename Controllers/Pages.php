<?php

namespace Controllers;

class Pages extends \App\Controller
{

    public $translates;

    public $controller = 'pages';
    public $translatesFile = 'pages';

    //Имя главной таблицы и таблицы с переводами
    public $table_name;
    public $translations_table;

    public function __construct()
    {

        $this->table_name = prefix . $this->controller;
        $this->translations_table = $this->table_name . '_translations';
    }

    public function index()
    {
        header("Location: /home/");
        exit();
    }

    public function c($params)
    {
        $this->header_grey = true;

        //Получаем url
        $url = $params[0];

        $page = $this->getItem($url);

        if (empty($page)) {
            throw new \App\Exceptions\InvalidRouteException();
        }

        //Загружаем SEO данные
        $this->setSeoParams($page);

        //Параметры хлебных крошек
        $breadcrumb_params = [
            'controller' => $this->controller,
            'id' => $page[0],
            'type' => 0,
        ];

        //Параметры к выводу страницы
        $params = [
            'page' => $page,
            'breadcrumb_params' => $breadcrumb_params,
        ];

        $params = array_merge($params, $this->getAboutUsParams());

        return $this->render('Pages', $params);
    }

    //Получаем информацию о странице
    public function getItem($url)
    {
        $query = [
            'first_table' => $this->table_name,
            'second_table' => $this->translations_table,
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
        $page = \App::$db->selectInner($query, true, $query_params);

        return $page[0];
    }

    public function getAboutUsParams()
    {
        $query = [
            'first_table' => $this->table_name,
            'second_table' => $this->translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["id = :id", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true]
            ],
        ];
        $query_params = [
            'id' => '13',
        ];
        
        $params['callus'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => $this->table_name,
            'second_table' => $this->translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["id = :id", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true]
            ],
        ];
        $query_params = [
            'id' => '8',
        ];
        
        $params['best_parent'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => $this->table_name,
            'second_table' => $this->translations_table,
            'join' => [
                ['id', '=', 'connect']
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true]
            ],
        ];
        $query_params = [
            'connect' => 8,
        ];

        $params['best'] = \App::$db->selectInner($query, true, $query_params);

        return $params;
    }
}
