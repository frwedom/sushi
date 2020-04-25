<?php

namespace Controllers;

class Error extends \App\Controller
{

    public function __construct() {
        $this->header_grey = true;
        //Загружаем переводы для статичных текстов
        $this->translates = $this->getTranslates(strtolower(\App::$language->getCurrentLanguage()));
    }

	public function error404() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        return $this->render('404');
	}
	public function error500() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        return $this->render('404');
	}

}