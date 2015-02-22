<?php
// set error reporting (development)
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);


/** The base path (from an OS perspective) for the config script */
define('BASE_PATH', dirname(__FILE__) . '/');

/** website domain */
define('BASEURL', 'http://localhost/verein/');

/** website folder compared to the domain root */
define('ROOT_FOLDER', '/verein/');

/** MySQL host name */
define('MYSQL_HOST', 'localhost');
/** MySQL user name */
define('MYSQL_USER', 'root');
/** MySQL password */
define('MYSQL_PASSWORD', '');
/** MySQL database */
define('MYSQL_DATABASE', 'clubpage');

/** main folder for PHP class files */
define('CLASSFOLDER', 'classes');
/** root directory for static content */
define('STATICFOLDER', 'static');
/** don't load the following static includes */
define('STATIC_IGNORE', 'admin');


/** default script include */
define('DEFAULT_CONTENT', 'views/content/page.php');

/** the URL parameter used to specify the output format (e.g. JSON) */
define('CONTENT_TYPE_PARAM', 'format');
?>