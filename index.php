<?php
// подробнее про index.php http://www.yiiframework.ru/doc/guide/ru/basics.entry
date_default_timezone_set('Europe/Moscow');

// путь к фреймворку Yii, при необходиомсти можно изменить
$yii = dirname(__FILE__) . '/framework/yii.php';
// путь к основному конфигурациооному файлу Yii, при необходиомсти можно изменить
$config = dirname(__FILE__) . '/protected/config/main.php';

// при работе сайта в "боевом" режиме следующие две строки рекомендуется закомментировать
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
defined('BETTIME_PRODUCTION') or define('BETTIME_PRODUCTION', false); // true - если это боевой сайт


require_once($yii);
date_default_timezone_set('Europe/London');
Yii::createWebApplication($config)->run();