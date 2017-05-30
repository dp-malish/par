<?php
/**
 * User: Пикс
 * Date: 18.05.2017
 */

define('URL','http://klopotenko.com');
define('RUBRICA',NULL);
define('CATEGORY','/category/12zakysky/');
define('MAX_PAGE',3);
$page='';

$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);
ini_set('max_execution_time','18000000'); //время выполнения скрипта

set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();

$pageText =new Par_curl();
//$page=$pageText->connectLow(URL.RUBRICA.CATEGORY.$page);
$page=$pageText->connectLow('http://klopotenko.com/remulad-iz-seldereja/');

$cat_page = phpQuery::newDocument($page);

$paginator = $cat_page->find('main>article');
foreach($paginator as $link){
    $x=pq($link)->html();

}
var_dump($x);
/*
// сделать caption donor
$paginator = $cat_page->find('main > div.row > div.col-xs-12 > article > header > h2 > a');
foreach($paginator as $link){
    $x=pq($link)->html();
    if((strripos($x,' (видео)')!==false))$x= stristr($x, ' (видео)', true);
    $arrForDB['caption'][]=$x;
    $url=pq($link)->attr('href');
    $arrForDB['donor'][]=$url;
}
*/
/*
// сделать short_text
$cat_page->find('main > div.row > div.col-xs-12 > article > div.genpost-entry-content > a')->remove();
$paginator = $cat_page->find('main > div.row > div.col-xs-12 > article > div.genpost-entry-content');
foreach($paginator as $link){
    $x=pq($link)->html();
    //$rest = substr("abcdef", -1);// returns "f"
    $arrForDB['short_text'][]=$x;
}
echo  print_r($arrForDB).'<br><br>';


$paginator = $cat_page->find('main > div.row > div.col-xs-12 > article > figure > a > img');

foreach($paginator as $link) {
    $x = pq($link)->attr('src');
    $mas_cat_url[] = $x;
}

var_dump($mas_cat_url);
*/