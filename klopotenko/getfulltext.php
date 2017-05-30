<?php
$start = microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();


$DB=new SQLi();

$sql='SELECT m.id, m.link_donor, y.paginator_full_text FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.full_text_donor IS NULL';

$res=$DB->arrSQL($sql);

foreach($res as $k=>$v){
    $pageText =new Par_curl();


    
    
    /*$sql='UPDATE sites_donor_options SET data=CURRENT_DATE WHERE id='.$v['id'];
    if($DB->boolSQL($sql))echo '<span style="background-color:#'.rand(0,9).'399ff">**************************Проведено!**************************</span><br><br>';else echo '<span style="background-color:red">Ошибка - не проведено...</span><br><br>';*/
}


///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);