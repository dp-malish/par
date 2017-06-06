<?php
$start=microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
ini_set('max_execution_time','18000000'); //время выполнения скрипта
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();


$DB=new SQLi();

$sql='SELECT m.id, m.link_donor, y.paginator_full_text FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.full_text_donor IS NULL';

$res=$DB->arrSQL($sql);

foreach($res as $k=>$v){

    $pageText =new Par_curl();
    $page=$pageText->connectLow($v['link_donor']);
    $cat_page = phpQuery::newDocument($page);

    $paginator = $cat_page->find($v['paginator_full_text']);
    foreach($paginator as $link){
        $text=pq($link)->html();

        $smile=[':)',':(',';)','🙂','😉'];

        $text=str_replace($smile,'',$text);
        if($DB->boolSQL('UPDATE sites_donor_link SET full_text_donor='.$DB->realEscapeStr($text).' WHERE id='.$v['id'])){
            echo 'Ссылка '.$v['link_donor'].' - добавлена<br>';
        }else echo '<span style="background-color:darkred">Ссылка '.$v['link_donor'].' - не добавлена</span><br>';
    }
}


///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);