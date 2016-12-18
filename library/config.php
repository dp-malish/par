<?php 
//настройки пподключения к сайту который парсим
define(URL, 		'http://videolamp.org/courses/master-php-lite'); //адрес сайта который будем парсить

//настройки подключения к БД чтобы записывать результаты парсинга
define(DB_HOST, 	'localhost');
define(DB_DATABASE, 'testpars');
define(DB_USERNAME, 'root');
define(DB_PASSWORD, '');

//настройки скрипта
define(SAVEPATH, 			'img/'); //папка сохранения картинок
define(BASEPATH, 			'library'); //папка с php библиотеками
define(COOKIE_PATH, 		realpath(dirname(__FILE__).'/../')); //куда сохранять куки для авторизации

error_reporting(E_ALL); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', '18000000'); //время выполнения скрипта

function del_postfix($name) { // убираем постфикс к картинке
	 
	 $posv= strripos($name, '?');	
	 if ($posv>0) { 
		 $del = substr($name, $posv, strlen($name) );
		 $name= substr_replace($name, '', $posv, strlen($name)) ;
	 }
	 	
	 return  $name;
}
