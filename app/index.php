<?php

// functions as dispatcher since this doesn't really have a front end
session_start();


	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
// grabs all the core classes
require('./core/config.core.php');
require(ROOT . '/core/baseClasses.core.php');
require(ROOT . '/core/classLoader.core.php');
require(ROOT . '/core/router.core.php');


// finds and runs the methods from the url
$route = new Router($_SERVER['REQUEST_URI']);
$route->findRoute();

$controllerName = $route->getController();
$action = $route->getAction();
$paramsExist = $route->getParamsExist();

$classManagament = new classLoader($controllerName, 'control');
$classManagament->loadClass();

$controller = new $controllerName($paramsExist);
if (method_exists($controller, $action)) {
	if ($controller->$action() === false) {
		echo "DISPATCHER FAILED";
		exit();
	} else {
		unset($controller);
	}
} else {
	echo "DISPATCHER FAILED";
	exit();
}