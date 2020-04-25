<?php

namespace App;

class Controller
{

    //Layout file path
    public $layoutFile = 'Views/Layout.php';

    public $v = '0.0.1';

    //Title & Meta tags
    public $title = '';
    public $description = '';
    public $keywords = '';
    public $author = 'frwedom';

    //Layout translate values
    public $layoutTranslates;

    public $translates;

    //Coming soon page
    public $comingSoon = array();
    public $comingSoonView = 0;
    public $comingSoonFile = 'Coming_soon';

    //admin settings
    public $admin_settings;

    //body classes
    public $bodyClasses;

    public $cities;
    public $building_types;

    public $layoutParams = [];

    public $scrollOff = false;

    public $header_grey = false;


    public $mail_to = [
        'tm_00@inbox.ru',
    ];

    public function __construct()
    {
        
    }

    public function getAdminSettings()
    {
        $query = [
            'first_table' => prefix . 'admin_settings',
            'second_table' => prefix . 'admin_settings_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["id = :id", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'id' => 1,
        ];

        $settings = \App::$db->selectInner($query, true, $query_params)[0];


        return $settings;
    }

    /**
     * Рендер макета - header & footer
     * @param $body
     * @return false|string
     */
    public function renderLayout($body)
    {
        
        extract($this->layoutParams);

        //Вывод
        ob_start();
        require ROOTPATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . "Layout.php";
        return ob_get_clean();
    }

    /**
     * Рендер страниц
     * @param $viewName - Имя файла view
     * @param array $params - Параметры которые нужно передать на страницу
     * @param string $values
     * @return false|string
     */
    public function render($viewName, array $params = [], $values = '')
    {

        //Загружаем данные для layout
        $this->layoutParams = $this->getLayoutParams();

        $this->admin_settings = $this->getAdminSettings();
        $this->comingSoon = $this->admin_settings;

        // Если заглушка включена, загружаем страницу с заглушкой
        if ($this->comingSoon['cs_active'] && !\App::$gates->checkAdminAuth()) {

            $this->setSEOParams($this->comingSoon);

            $this->comingSoonView = 1;

            if ($this->comingSoonView == 1) {
                echo $this->renderComingSoon($this->comingSoonFile);
                exit();
            }
        }


        $viewFile = ROOTPATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewName . '.php';
        extract($params);
        ob_start();
        require $viewFile;
        $body = ob_get_clean();
        ob_end_clean();
        if (defined('NO_LAYOUT')) {
            return $body;
        }

        return $this->renderLayout($body);
    }

    /**
     * Рендер фиксированных частей
     * @param $viewName - Имя файла view
     * @param array $params - Параметры которые нужно передать
     * @return false|string
     */
    public function renderParts($viewName, array $params = [])
    {
        $viewFile = ROOTPATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Parts' . DIRECTORY_SEPARATOR . $viewName . '.php';
        extract($params);
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }

    /**
     * Рендер заглушки
     * @param $viewName - Имя файла заглушки
     * @param array $params - Параметры которые нужно передать
     * @return false|string
     */
    public function renderComingSoon($viewName, array $params = [])
    {
        $viewFile = ROOTPATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewName . '.php';
        extract($params);
        ob_start();
        require $viewFile;
        return ob_get_clean();
    }

    /**
     * Получаем переводы для страницы
     * @param $fileName - Имя файла с переводами
     * @return bool|mixed
     */
    public function getTranslates($fileName)
    {

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Translates' . DIRECTORY_SEPARATOR . ucfirst($fileName) . '.php')) {
            echo 'Cant find translations file: ' . $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Translates' . DIRECTORY_SEPARATOR . ucfirst($fileName) . '.php';
            return false;
        }

        $response = require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Translates' . DIRECTORY_SEPARATOR . ucfirst($fileName) . '.php';

        return $response;
    }

    /**
     * @param $params - Параметры которые нужно передать
     */
    public function setSEOParams($params)
    {

        if (empty($params['seo_title'])) {
            $this->title = $params['name'];
        } else {
            $this->title = $params['seo_title'];
        }


        $this->description = $params['seo_description'];
        $this->keywords = $params['seo_keywords'];
    }

    /**
     * Получаем параметры артикля
     * @param $articleName - Имя артикля в базе
     * @return mixed
     */
    public function getArticleParams($articleName)
    {
        $query = [
            'select' => [
                ['name', false],
                ['seo_title', false],
                ['seo_description', false],
                ['seo_keywords', false],
            ],
            'first_table' => prefix . 'admin_articles',
            'second_table' => prefix . 'admin_articles_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["controller = :controller", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'controller' => $articleName,
        ];

        $article = \App::$db->selectInner($query, true, $query_params)[0];

        return $article;
    }

    public function getLayoutParams()
    {

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'connect' => 6,
        ];

        $params['social'] = \App::$db->selectInner($query, true, $query_params);

        $query = [
            'first_table' => prefix . 'main_items',
            'second_table' => prefix . 'main_items_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'connect' => 10,
        ];

        $params['store'] = \App::$db->selectInner($query, true, $query_params);

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
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

        $params['header_logo'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
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
            'id' => 6,
        ];

        $params['footer_logo'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'company',
            'second_table' => prefix . 'company_translations',
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

        $params['addresses_parent'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'connect' => 3,
        ];

        $params['addresses'] = \App::$db->selectInner($query, true, $query_params);

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'connect' => 5,
        ];

        $params['mails'] = \App::$db->selectInner($query, true, $query_params);

        $query = [
            'first_table' => prefix . 'company_items',
            'second_table' => prefix . 'company_items_translations',
            'join' => [
                ['id', '=', 'connect'],
            ],
            'where' => [
                ["active = 1", true, 'AND'],
                ["connect = :connect", true, 'AND'],
            ],
            'order' => [
                ['the_order DESC', true],
            ],
        ];
        $query_params = [
            'connect' => 4,
        ];

        $params['phones'] = \App::$db->selectInner($query, true, $query_params);

        $query = [
            'first_table' => prefix . 'company',
            'second_table' => prefix . 'company_translations',
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
            'id' => 9,
        ];

        $params['footer_info'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'company',
            'second_table' => prefix . 'company_translations',
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
            'id' => 2,
        ];

        $params['contacts_info'] = \App::$db->selectInner($query, true, $query_params)[0];

        $query = [
            'first_table' => prefix . 'pages',
            'second_table' => prefix . 'pages_translations',
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
            'id' => 6,
        ];

        $params['contacts_page'] = \App::$db->selectInner($query, true, $query_params)[0];

        $result = [];
        \App::$menu->search(\App::$menu->tree, 1, $result);
        $params['header_menu'] = $result;

        $result = [];
        \App::$menu->search(\App::$menu->tree, 6, $result);
        $params['footer_menu'] = $result;

        $this->layoutParams = $params;

        return $params;
    }
}
