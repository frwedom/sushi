<?php
define('ROOTPATH', __DIR__);

require __DIR__.'/App/App.php';

App::init();
App::$kernel->launch();



