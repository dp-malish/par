<?php
$start = microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();


$DB=new SQLi();

$sql='SELECT m.id,y.site,y.paginator_link,y.paginator_img_s,y.paginator_short_text,y.paginator_short_text_del_link,m.rubrika,m.rubrika_name,m.category,m.category_name,m.page,m.page_end,m.max_page FROM sites_donor_options m
LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.data IS NULL OR m.data!=CURRENT_DATE';

$res=$DB->arrSQL($sql);

foreach($res as $k=>$v){
    $short_url=$v['site'].$v['rubrika'].$v['category'];
    $page=($v['max_page']=='')?1:$v['max_page'];
    $pageText =new Par_curl();

    for($y=1;$y<=$page;$y++){
        $arrForDB=[];

        echo '<span style="background-color:#55994d">'.$v['category_name'].' стр.'.$y.'</span><br>';

        $url=($y==1)?$short_url:$short_url.$v['page'].$y.$v['page_end'];

        $cat_page=phpQuery::newDocument($pageText->connectLow($url));

        //ссылка
        $paginator=$cat_page->find($v['paginator_link']);
        foreach($paginator as $link){$arrForDB['donor'][]=$DB->realEscapeStr(pq($link)->attr('href'));}

        //рисунок
        $paginator=$cat_page->find($v['paginator_img_s']);
        foreach($paginator as $link){$arrForDB['img_donor'][]=$DB->realEscapeStr(pq($link)->attr('src'));}

        //короткий текст
        if($v['paginator_short_text_del_link']!='')
            $cat_page->find($v['paginator_short_text'].$v['paginator_short_text_del_link'])->remove();
        $paginator=$cat_page->find($v['paginator_short_text']);
        foreach($paginator as $link){$arrForDB['short_text_donor'][]=$DB->realEscapeStr(trim(pq($link)->html()));}

        $count=count($arrForDB['donor']);

        for($i=0;$i<$count;$i++){
            $sql='INSERT INTO sites_donor_link (id,id_opt,site,link_donor,img_s_donor,short_text_donor,rubrika,category) VALUES 
            (NULL,"'.$v['id'].'","'.$v['site'].'",'.$arrForDB['donor'][$i].','.$arrForDB['img_donor'][$i].','.$arrForDB['short_text_donor'][$i].','.'"'.$v['rubrika_name'].'","'.$v['category_name'].'");';
            echo 'Категория '.$v['category_name'].' - '.(($DB->boolSQL($sql))?'добавлена':'<span style="background-color:darkred">ошибка</span>').' - '.$arrForDB['donor'][$i].'<br>';
        }
    }
    $sql='UPDATE sites_donor_options SET data=CURRENT_DATE WHERE id='.$v['id'];
    if($DB->boolSQL($sql))echo '<span style="background-color:#'.rand(0,9).'399ff">**************************Проведено!**************************</span><br><br>';else echo '<span style="background-color:red">Ошибка - не проведено...</span><br><br>';
}


///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);