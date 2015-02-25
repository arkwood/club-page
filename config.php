<?php
// set error reporting (development)
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR);
ini_set('display_errors', 1);


/** The base path (from an OS perspective) for the config script */
define('BASE_PATH', dirname(__FILE__) . '/');

/** website domain */
define('BASEURL', 'http://localhost');

/** website folder compared to the domain root */
define('ROOT_FOLDER', '/');

/** MySQL host name */
define('MYSQL_HOST', 'localhost');
/** MySQL user name */
define('MYSQL_USER', 'root');
/** MySQL password */
define('MYSQL_PASSWORD', '');
/** MySQL database */
define('MYSQL_DATABASE', 'clubpage');

/** MySQL datetime format */
define('MYSQL_DATE_TIME_FORMAT', 'Y-m-d H:i:s');
/** MySQL date format */
define('MYSQL_DATE_FORMAT', 'Y-m-d');

/** main folder for PHP class files */
define('CLASSFOLDER', 'classes');
/** root directory for static content */
define('STATICFOLDER', 'static');
/** don't load the following static includes */
define('STATIC_IGNORE', 'admin');
/** don't load the following static includes in the backend */
define('STATIC_IGNORE_BACKEND', 'clubpage.js,clubpage.css');


/** default script include for the frontend */
define('DEFAULT_CONTENT', 'views/content/page.php');
/** default script include for the backend */
define('DEFAULT_CONTENT_BACKEND', 'backend.php');

/** the URL parameter used to specify the output format (e.g. JSON) */
define('CONTENT_TYPE_PARAM', 'format');


define('DEFAULT_TIME_ZONE', 'Australia/Melbourne');
?>