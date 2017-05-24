<?php
/**
 * User: Пикс
 * Date: 24.05.2017
 */
$start = microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();


$DB=new SQLi();

$sql='SELECT m.id,m.img_s_donor

 FROM sites_donor_link m
LEFT JOIN sites_donor_options  y ON m.id_site = y.id WHERE m.data IS NULL OR m.data!=CURRENT_DATE';

$res=$DB->arrSQL($sql);











///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);