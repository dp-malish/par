<?php 
/*
Парсер сайтов с авторизацией для вытаскивания любых данных
Кротов Роман
*/
$start = microtime(true);

set_include_path(get_include_path().PATH_SEPARATOR.'library/'); 
set_include_path(get_include_path().PATH_SEPARATOR.'phpQuery/'); 

require('config.php');

function __autoload( $className ) {require_once( "$className.php" );}


echo "<br>".date('H:i:s')." Начинаем парсинг ".URL;

$pageText =new Curl();
$page=$pageText->get_page(URL);

$cat_page = phpQuery::newDocument($page); 

$paginator = $cat_page->find('table.wpp_divider > tbody > tr > td > img');
/*
	foreach ($paginator as $link){
		$pages=pq($link)->html();		
	}
	*/
	
$width=pq($paginator)->attr('width');
	
echo "<br>".date('H:i:s')." Получили ширину картинки: ".$width;

$src=pq($paginator)->attr('src');
echo "<br>".date('H:i:s')." Получили url картинки: ".$src;

$saveto=SAVEPATH."/1.png";
$res=file_put_contents($saveto, file_get_contents($src)); //сохранение картинки в файл
if ($res) echo "<br>".date('H:i:s')." Записали картинку в папку: ".__DIR__."/".$saveto;	

$db=new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$params['width']=$width; //массив с именем поля и значением которое вставляем
$db->insert($params,'images');

//////////////////////////////////////////////////////////////////////////
$time = microtime(true) - $start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.', $time);