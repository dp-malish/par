<?php
$start = microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();

$arrForDB=[];
$DB=new SQLi();

$sql='SELECT m.id,y.site,y.paginator_link,y.paginator_img,y.paginator_short_text,m.rubrika,m.rubrika_name,m.category,m.category_name,m.page,m.page_end,m.max_page FROM sites_donor_options m
LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.data IS NULL OR m.data!=CURRENT_DATE';

$res=$DB->arrSQL($sql);

foreach($res as $k=>$v){
    $short_url=$v['site'].$v['rubrika'].$v['category'];
    $page=($v['max_page']=='')?1:$v['max_page'];
    $pageText =new Curl();

    for($i=1;$i<=$page;$i++){

        echo '<span style="background-color:#55994d">'.$v['category_name'].' стр.'.$i.'</span><br>';

        $url=($i==1)?$short_url:$short_url.$v['page'].$i.$v['page_end'];

        $cat_page=phpQuery::newDocument($pageText->connectLow($url));
        //ссылка
        $paginator = $cat_page->find($v['paginator_link']);
        foreach($paginator as $link){$arrForDB['donor'][]=$DB->realEscapeStr(pq($link)->attr('href'));}

        //рисунок
        $paginator = $cat_page->find($v['paginator_img']);
        foreach($paginator as $link){$arrForDB['img_donor'][]=$DB->realEscapeStr(pq($link)->attr('src'));}

        //короткий текст
        $cat_page->find($v['paginator_short_text'].'>a')->remove();
        $paginator = $cat_page->find($v['paginator_short_text']);
        foreach($paginator as $link){$arrForDB['short_text_donor'][]=$DB->realEscapeStr(trim(pq($link)->html()));}

        var_dump($arrForDB);
    }






    //$sql='UPDATE sites_donor_options SET data=CURRENT_DATE WHERE id='.$v['id'];


    //if($DB->boolSQL($sql))echo '<span style="background-color:#3399ff">Проведено!</span><br><br>';else echo '<span style="background-color:red">Ошибка - не проведено...</span><br><br>';
}


///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);