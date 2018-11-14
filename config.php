<?php

/******** CONFIG FILE FOR APP **********/

define('database_type', 'mysqli');
define('database_name', '');
define('server', 'localhost');
define('username', '');
define('password', '');
define('charset', 'utf8');
// optional
define('port', 3306);



define('THEME_NAME', 'default');

define('TEMPLATE_EXTENSION', '.tpl');

/*
 * Default page of the application.
 * no need to slash sign / at the end of the paths
 * do not write like that 'app/view/path to...' no need to the 'view' word.
 */
define('DEFAULT_PAGE', 'common/home');



date_default_timezone_set('Europe/Istanbul');

/*
 * change the header and footer file location the way you want.
 * No need to write your theme path.Because these files must be in the view folder
 * So, you just write as like that home/xxx.php
 * When the const HEADER_FILE called by PHP.
 * Never use extension
 */
define('HEADER_FILE', 'common/header');
define('FOOTER_FILE', 'common/footer');




define("BASE_URL", 'http://localhost/rex/');


define("SHOW_ERROR", true);

define("FRIENDLY_URL", false);


define('LOGIN_URL', 'common/login');


define('404_PAGE', 'common/404');
?>
