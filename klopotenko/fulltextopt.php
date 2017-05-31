<?php
$start=microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
ini_set('max_execution_time','18000000'); //время выполнения скрипта
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();

if(empty($_GET)){
    echo '<ul>
        <li><a href="?getcaption">Скопировать название статей</a></li>
        <li><a href="?removehlam">Очистить текст от стилей и мусора...</a></li>
     
        </ul>';
}else{$DB=new SQLi();

    if(isset($_GET['getcaption'])){
        $sql='SELECT m.id, m.link_donor, m.full_text_donor, y.paginator_full_text_caption, y.paginator_full_text_caption_parse
FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id
WHERE m.full_text_donor IS NOT NULL AND m.full_text_caption IS NULL';
        $res=$DB->arrSQL($sql);
        foreach($res as $k=>$v){
            $cat_page = phpQuery::newDocument($v['full_text_donor']);

            $paginator = $cat_page->find($v['paginator_full_text_caption']);
            foreach($paginator as $link){
                $x=pq($link)->html();
                if($v['paginator_full_text_caption_parse']!=''){
                    $x=trim(str_replace($v['paginator_full_text_caption_parse'],'',$x));
                }
                if($DB->boolSQL('UPDATE sites_donor_link SET full_text_caption='.$DB->realEscapeStr($x).' WHERE id='.$v['id']))
                echo 'Заголовок по ссылке '.$v['link_donor'].' - добавлен<br>';
                else echo '<span style="background-color:darkred">'.'Ошибка!!! Заголовок по ссылке '.$v['link_donor'].' - не добавлен</span><br>';
            }
        }
        echo '<p>Следущий этап <a href="?removehlam">Очистить текст от стилей и мусора...</a></p>';
    }elseif(isset($_GET['removehlam'])){
        $sql='SELECT m.id, m.link_donor, m.full_text_donor, y.paginator_full_text_remove

FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.full_text_donor IS NOT NULL LIMIT 1';


    }
}







///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);