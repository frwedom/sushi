<?php
session_start();

define('prefix', "c_");

define('images_path', "/uploads/images/");
define('video_path', "/uploads/video/");
define('audio_path', "/uploads/audio/");

//Время жизни 1 год. Дата создания 03.12.2019
define('yandex_token', "AgAAAAA7MtcpAAYCewROaeT-W0wMhVZ_TBD40Y0");

define('vk_id', '7233294');
define('vk_secret', 'guTDMcFOlphfR7W3j0Jt');
define('vk_URL', 'https://premierkazakhstan.kz/vk/vkok');

$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                $_SERVER['REQUEST_URI']; 
define('page_link', $link);
