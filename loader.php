<?php
require_once 'config.php';
require_once 'function.php';

/*
 * Find out if this is an admin request
 */
// TODO: sub-directories not handled. Remove http, host and port from BASE_URL to find string position of /admin
$admin = strpos($_SERVER["PHP_SELF"], 'admin') == 1;


/*
 * initialize template engine
 */
 // load Twig core
require_once BASE_PATH . 'lib/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
// load Twig i18n extension
require_once BASE_PATH . 'lib/twig-extensions/lib/Twig/Extensions/Autoloader.php';
Twig_Extensions_Autoloader::register();
// template folder
$twigLoader = new Twig_Loader_Filesystem(BASE_PATH . 'templates');
// declare twig environment
$twig = new Twig_Environment($twigLoader);
$twig->addGlobal('BASEURL', BASEURL);
/*, array(
    'cache' => BASE_PATH . 'tpl_compile/cache',
)); */
// add extension for i18n
$twig->addExtension(new Twig_Extensions_Extension_I18n());

$GLOBALS["twig"] = $twig;

/*
 * initialize session and set header
 */
session_start();
if (!$GLOBALS["headerAlreadySent"]) {
	if ($_GET[CONTENT_TYPE_PARAM] == 'json') {
	    header('Content-Type: text/json; charset=utf-8');
	}
	else if ($_GET[CONTENT_TYPE_PARAM] == 'xml') {
	    header('Content-Type: text/xml; charset=utf-8');
	}
	else {
	    header('Content-Type: text/html; charset=utf-8');
	}
}


/*
 * connect to the database
 */
mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);

/*
 * load classes
 */ 
function includeClass($fs) {
	$subDirs = array();

	// object is a directory, add it to the list
	if (is_dir($fs)) {
		foreach (scandir($fs) as $entry) {
			// exclude . and ..
			if ($entry === "." || $entry === "..")
				continue;
			
			array_push($subDirs, $fs . '/' . $entry);
		}
	}
	
	// file is a class - load it
	else if (is_file($fs) && strpos($fs, ".php") == strlen($fs) - 4) {
		require_once $fs;
	}
	
	// finally include sub dirs
	foreach ($subDirs as $dir)
		includeClass($dir);
}
require_once CLASSFOLDER . '/dbobject.php';
includeClass(dirname(__FILE__) . '/' . CLASSFOLDER);


/**
 * load Javascript and CSS
 */
function includeStatic($fs, $admin) {
	$result = '';
	$subElement = array();


	// object is a directory, add it to the list
	if (is_dir($fs)) {

		foreach (scandir($fs) as $entry) {
			// exclude . and ..
			if ($entry === "." || $entry === "..")
				continue;
			
			// exclude ignore list
			$ignoreList = "";
            if ($admin) {
                $ignoreList = STATIC_IGNORE_BACKEND;
            }
            else {
                $ignoreList = STATIC_IGNORE;    
            }
			$ignoreList = explode(",", $ignoreList);
			$ignoreCurrentEntry = false;
			foreach ($ignoreList as $ignore) {
				if ($entry == $ignore)
					$ignoreCurrentEntry = true;
			}
			if ($ignoreCurrentEntry)
				continue;
			
			$entry = $fs . '/' . $entry;
			if (is_dir($entry)) {
				array_push($subElement, $entry);				
			}
			else {
				// file is a match - load it by prepending it to the result
				if (strpos($entry, ".css") == strlen($entry) - 4)
					$result .= '<link href="' . BASEURL . str_ireplace(dirname(__FILE__), '', $entry) . '?timestamp=' . (filemtime($entry)) . '" rel="stylesheet" type="text/css" />
	';
				else if (strpos($entry, ".js") == strlen($entry) - 3)
					$result .= '<script type="text/javascript" src="' . BASEURL . str_ireplace(dirname(__FILE__), '', $entry) . '?timestamp=' . (filemtime($entry)) . '"></script>
	';
			}
		}
	}
	
	// finally include sub dirs
	foreach ($subElement as $element) {		
			$result .= includeStatic($element, $admin);
	}		
	
	return $result;
}

$GLOBAL[GLOBALSTATICCONTENT] = includeStatic(dirname(__FILE__) . '/' . STATICFOLDER, $admin);


/*
 * locale and internationalization
 */
// fall back to locale
$providedLocale = DEFAULT_LOCALE;
// read first the session, then the get, then the post 
if (isset($_SESSION["lang"]))
	$providedLocale = $_SESSION["lang"];
if (isset($_GET["lang"]))
	$providedLocale = $_GET["lang"];
if (isset($_POST["lang"])) 
	$providedLocale = $_POST["lang"];
// check if locale really exists - fallback to default
if (strpos(AVAILABLE_LOCALES, $providedLocale) === false) {
	$providedLocale = DEFAULT_LOCALE;
}
// finally set locale and store it in session
setlocale(LC_TIME, $providedLocale);
$_SESSION["lang"] = $providedLocale;
define('CURRENT_LOCALE', $providedLocale);


date_default_timezone_set(DEFAULT_TIME_ZONE);


?>