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

/*
//Вариант 1 - Парсинг по карте сайта категорий http://spanch-bob.org/sitemap.xml
$xml_file=file_get_contents (URL."/sitemap.xml");
$xml = simplexml_load_string($xml_file);

foreach($xml->url as $item){
  $url=toArray($item->loc);
  $url=$url[0];
  //$url=$item->loc;
  
  $pos = strpos($url, ".html");
  if ($pos === false)
    $mas_cat_url[]=$url;
  else
     $mas_page_url[]=$url;
}
*/
 
//Вариант 2 - парсинг с помощью phpQuery из тела сайта http://spanch-bob.org/
$pageText =new Curl();
$page=$pageText->get_page(URL);
$cat_page = phpQuery::newDocument($page); 
$paginator = $cat_page->find('ul#menu4 > li > a');
$cats=0;
foreach ($paginator as $link){
 // $a=pq($link)->html();	
  $url=pq($link)->attr('href');
  $mas_cat_url[]=$url;
  
  echo "<br>".date('H:i:s')." Открываем категорию ".$url;  
  $pagec=$pageText->get_page($url);
  $pagec = phpQuery::newDocument($pagec); 
  $pagination = $pagec->find('#dle-content > div.navigation > a');
  
  foreach ($pagination as $lk){
    $all_pages=pq($lk)->html();    
  }
  
  echo "<br>".date('H:i:s')." Найдено страниц в категории ".$all_pages;  
  
  $k=0;
  while ($k<$all_pages){
    $k++;
    $url_finish=$url."page/".$k."/";
    echo "<br>".date('H:i:s')." Открываем страницу категории ".$url_finish;  
    
    $pageincat=$pageText->get_page($url_finish);
    $pageincat = phpQuery::newDocument($pageincat); 
    $prewviewgame = $pageincat->find('#dle-content > div > a');
    
    $g=0;
     foreach ($prewviewgame as $pg){
      $url_game=pq($pg)->attr('href');   
      echo "<br>".date('H:i:s')." Получили страницу игры ".$url_game;  
        
         $pagegame=$pageText->get_page($url_game);
         $pagegame = phpQuery::newDocument($pagegame); 
         
         $datagame = $pagegame->find('#dle-content > div > div.fulltext');
         
         $h1=$datagame->find('h1');
         $h1=pq($h1)->html();  
         echo "<br>".date('H:i:s')." Получили название игры ".$h1;  
           
         $img=$datagame->find('img');
         $src=URL.pq($img)->attr('src');  
         
         echo "<br>".date('H:i:s')." Получили картинку игры ".$src;  
         
         $datagame=pq($datagame)->html(); 
         $description=strip_tags ($datagame);
         echo "<br>".date('H:i:s')." Получили описание игры ".$description;  
               
         $filegame = $pagegame->find('div.game-block > div.game-playing > object > embed');
         $filegame=URL.pq($filegame)->attr('src');  
         echo "<br>".date('H:i:s')." Получили файл игры ".$filegame;  
         
      
      $g++;
      if ($g>0) break;//для тестов
    }
    
    
    if ($k>0) break;//для тестов
  }
  
  
  $cats++;
  if ($cats>0) break;//для тестов
}


foreach($mas_cat_url as $item_url){
  $cat=str_replace("http://spanch-bob.org/", "", $item_url);
 
  $path=__DIR__."/content/".$cat;
  if (!file_exists($path)) mkdir($path, 0777, true);
  
  $mas_cat_url2[]=$cat;
}

  
//////////////////////////////////////////////////////////////////////////
$time = microtime(true) - $start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.', $time);