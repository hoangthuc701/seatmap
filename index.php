<?php

// Turn off all error reporting
//error_reporting(0);

session_start();
require_once "smarty/BaseSmarty.php";
require_once('config.php');


$smarty = new BaseSmarty();
$logined = isset($_SESSION['logined'])? $_SESSION['logined']:null;

if (!$logined) {
    if ((isset($_GET['controller']) and $_GET['controller'] != 'authenticateController') or (!isset($_GET['controller'])))
        header("Location: /seatmap/authenticate/signin");

}

if (!isset($_GET['controller'])) {
    $smarty->assign('activePage', 'dashboard');
    $smarty->display('index.tpl');
    return;
}
$controller = $_GET['controller'];
$controller_path = 'Controller/' . $controller . '.php';

if (file_exists($controller_path)) {
    require_once $controller_path;
    $index = new $controller();
    $action = $_GET['action'];


    if (is_callable([$index, $action])) {
        $index->$action();
        return;
    }
}
$smarty->display('404notfound.tpl');
