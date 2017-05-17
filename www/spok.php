<?php
/**
 * Created by PhpStorm.
 * User: Пикс
 * Date: 17.05.2017
 * Time: 14:17
 */
define('URL','https://spok.ua');

$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);

set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');

spl_autoload_register();


//$Cash=new Cache_File('../cache_all/parse/');


//взять категорию
$pageText =new Curl();
$page=$pageText->connectLow(URL);
//echo $page;


$cat_page = phpQuery::newDocument($page);
$cat_page->find('.control-site-menu:first > div.control-site-menu-popup >ul>li>a>i')->remove();
$paginator = $cat_page->find('.control-site-menu:first > div.control-site-menu-popup >ul>li>a');
//$paginator = $paginator->find('.control-site-menu:first');

foreach ($paginator as $link) {

    $a=pq($link)->html();
    //$a=pq($a)->remove('i');
    //$a=pq($link)->html();
    //$url = pq($link)->attr('href');
    $mas_cat_url[] = $a;
}
var_dump($mas_cat_url);

//echo $paginator;

//echo $page;