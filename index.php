<?php
     // P3P Policies (http://www.w3.org/TR/2002/REC-P3P-20020416/#compact_policies)
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
// подробнее про index.php http://www.yiiframework.ru/doc/guide/ru/basics.entry
date_default_timezone_set('Europe/Moscow');

// путь к фреймворку Yii, при необходиомсти можно изменить
$yii = dirname(__FILE__) . '/framework/yii.php';
// путь к основному конфигурациооному файлу Yii, при необходиомсти можно изменить
$config = dirname(__FILE__) . '/protected/config/main.php';

// при работе сайта в "боевом" режиме следующие две строки рекомендуется закомментировать
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
defined('BETTIME_PRODUCTION') or define('BETTIME_PRODUCTION', true); // true - если это боевой сайт


require_once($yii);
date_default_timezone_set('Europe/London');
Yii::createWebApplication($config)->run();


/*
Объединить новости про откртиые и бесплатный сервис
убрать рождество
Резльутатов сделать больше справа - повысоте
Отцентровать рекорды по центру
Сузить строки по высоте
Объединить даты
Перенос сделать в ставке через 5символов


*/
