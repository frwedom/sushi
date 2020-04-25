<?php
namespace App;

use App;

class Language {
    public $defaultLang;
    private $table_name = prefix . 'languages';

    public function __construct() {


        $languages = $this->getLanguages();

        //Установка языка по умолчанию
        foreach ($languages as $v) {
            if ($v['default_language']) {
                $this->defaultLang = $v['code'];
            }
        }

        //Если язык поменяли
        if (!empty($_GET["language"])) {

            foreach ($languages as $v) {
                if (strtolower($_GET["language"]) === $v['code']) {
                    $_SESSION['site_lang'] = $v['code'];
                }
            }

        }

        //Если язык не установлен
        if (empty($_SESSION["site_lang"])) {
            $_SESSION["site_lang"] = $this->defaultLang;
        }

    }

    public function getLanguages() {
        $query = [
            'table'      => $this->table_name,
            'where'      => [
                ["active = 1", 'AND'],
            ],
            'order'      => [
                'id' => 'ASC',
            ]
        ];

        $languages = \App::$db->select($query);

        return $languages;
    }

    public function getCurrentLanguage() {
        return $_SESSION['site_lang'];
    }
}