<?php
// session_destroy();
session_start();
include '../app.php';
// function ParseURL_ModRewrite()
// {
// 	//Чиста URI
// 	$uri = preg_replace('#[a-z0-9]+\.[a-z0-9]+$#i', '', $_SERVER['REQUEST_URI']);

// 	$get_reqs = explode('/', $uri);

// 	for ($i = 0; $i < sizeof($get_reqs); $i++) {
// 		if ($get_reqs[$i] == '' && ($i + 1) == sizeof($get_reqs))
// 			break;

// 		$_GET['value' . ($i - 1)] = $get_reqs[$i];
// 	}
	
// }
// ParseURL_ModRewrite();
// Вывод на экран всех параметров GET
// print_r($_GET);

// Удалить временное хранилище (файл сессии) на сервере
?>