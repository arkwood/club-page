<?php
require_once '../loader.php';


$result = true;

if (array_key_exists("login", $_POST)) {
    // perform login operation
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result = User::login($username, $password);
}
else if (array_key_exists("logout", $_GET)) {
    User::logout();
}



if (!User::isLoggedIn()) {
    // user is not logged in -> render login form
    $html = $twig->render('admin/layout/login.html', array("result" => $result));
    addScriptContent($html);
}
else {
    // user is logged in -> render dashboard
    require 'views/layout/dashboard.php';
}

require_once 'index.php';
?>