<?php
$start = microtime(true);

$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();

$DB=new SQLi();

$sql='SELECT m.id,y.site,m.rubrika,m.rubrika_name,m.category,m.category_name,m.page,m.page_end,m.max_page FROM sites_donor_options m
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

        $paginator = $cat_page->find('main > div.row > div.col-xs-12 > article > header > h2 > a');
        foreach($paginator as $link){
            $donor=$DB->realEscapeStr(pq($link)->attr('href'));
            $sql='INSERT INTO sites_donor_link VALUES (NULL,'.$donor.',"'.$v['rubrika_name'].'","'.$v['category_name'].'",NULL);';
            echo 'Категория '.$v['category_name'].' стр. '.$i.' - '.(($DB->boolSQL($sql))?'добавлена':'<span style="background-color:red">ошибка</span>').' - '.$donor.'<br>';
        }
    }
    $sql='UPDATE sites_donor_options SET data=CURRENT_DATE WHERE id='.$v['id'];
    if($DB->boolSQL($sql))echo '<span style="background-color:#3399ff">Проведено!</span><br><br>';else echo '<span style="background-color:red">Ошибка - не проведено...</span><br><br>';
}


///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);