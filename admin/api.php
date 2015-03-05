<?php
header('Content-Type: text/json; charset=utf-8');
$GLOBALS["headerAlreadySent"] = true;
require_once dirname(__FILE__) . '/../loader.php';


if (User::isLoggedIn()) {
	/*
	 * POST Methods
	 */
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$action = $_POST["action"];
		require_once 'rest/' . $action . '.php';
	}
	
	/*
	 * POST Methods
	 */
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$action = $_GET["action"];
		require_once 'rest/' . $action . '.php';
	}
	
	echo json_encode($GLOBALS[GLOBALSCRIPTCONTENT]);	
}
else {
	// not logged in
	echo json_encode("{ result: 0, message: 'Authentication failed.'}");	
}

?>