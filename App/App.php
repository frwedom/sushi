<?php

class App

{

    public static $functions;

    public static $router;

    public static $db;

    public static $kernel;

    public static $gates;

    public static $language;

    public static $breadcrumbs;

    public static $pagination;

    public static $menu;

    public static $users;

    public static $mail;

    public static $upload;

    public static function init()
    {
        require_once ROOTPATH . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Config.php';
        spl_autoload_register(['static', 'loadClass']);
        static::bootstrap();
        set_exception_handler(['App', 'handleException']);
    }

    public static function bootstrap()
    {
        static::$functions = new App\Functions();
        static::$router = new App\Router();
        static::$kernel = new App\Kernel();
        static::$db = new App\Db();
        static::$gates = new App\Gates();
        static::$language = new App\Language();
        static::$breadcrumbs = new App\Breadcrumbs();
        static::$pagination = new App\Pagination();
        static::$menu = new App\Menu();
        static::$users = new App\Users();
        static::$mail = new App\Mail();
        static::$upload = new App\Upload();
    }

    public static function loadClass($className)
    {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        require_once ROOTPATH . DIRECTORY_SEPARATOR . $className . '.php';
    }

    public static function handleException(Throwable $e)
    {

        if ($e instanceof \App\Exceptions\InvalidRouteException) {
            echo static::$kernel->launchAction('Error', 'error404', [$e]);
        } else {
            echo static::$kernel->launchAction('Error', 'error500', [$e]);
        }
    }
}
