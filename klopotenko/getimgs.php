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

$sql='SELECT m.id,m.img_s_donor,y.img_s_dir FROM sites_donor_link m
LEFT JOIN sites_donor_options  y ON m.id_opt = y.id WHERE y.img_s_dir IS NOT NULL';

$res=$DB->arrSQL($sql);
if($res){
    foreach($res as $k => $v) {
        $path = realpath(__DIR__) . $v['img_s_dir'];
        //echo $path . '<br>';
        if (!file_exists($path)) mkdir($path, 0777, true);
    }
    echo 'Каталоги для изображений обновлены<br>';
}











///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);