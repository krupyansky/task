<?php 
// Подключение автозагрузчика
require_once __DIR__ . '/../vendor/autoload.php';

// Инициализация названия контролера и действия
$controller = 'Main';
$controller .= 'Controller';
$action = $_GET['action'] ?? 'index';

// Роутинг
$class = '\\app\\controllers\\' . $controller;
if (class_exists($class)) {
	$controller = new $class;
	if ($action == 'index') {
		$controller->actionIndex();
	} elseif ($action == 'add-task') {
		$controller->actionAddTask();
	} elseif ($action == 'checkout') {
		$controller->actionCheckout();
	} elseif ($action == 'logout') {
		$controller->actionLogout();
	} elseif ($action == 'update') {
		$controller->actionUpdate();
	} elseif ($action == 'update-status') {
		$controller->actionUpdateStatus();
	} else {
		die('Страницы не существует');
	}
} else {
	die('Страницы не существует');
}
