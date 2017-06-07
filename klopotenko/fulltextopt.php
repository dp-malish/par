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
        <li><a href="?removehlam">Очистить текст от мусора...</a></li>
        <li><a href="?removestyle">Очистить текст от стилей и атрибутов...</a></li>
     
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
            //echo $v['full_text_donor'];
        }
        echo '<p>Следущий этап <a href="?removehlam">Очистить текст от мусора...</a></p>';
    }elseif(isset($_GET['removehlam'])){
        $sql='SELECT m.id, m.link_donor, m.full_text_donor, y.paginator_full_text_remove
FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.full_text_donor IS NOT NULL';
        $res=$DB->arrSQL($sql);
        foreach($res as $k=>$v){
            $remove_parts=explode('?#',$v['paginator_full_text_remove']);
            $cat_page = phpQuery::newDocument($v['full_text_donor']);

            foreach($remove_parts as $del_par){
                $cat_page->find($del_par)->remove();
            }

            $paginator=preg_replace('/<!--(.*?)-->/','',$cat_page->contents());
            
            $sql='UPDATE sites_donor_link SET full_text_donor='.$DB->realEscapeStr($paginator).' WHERE id='.$v['id'];

            if($DB->boolSQL($sql))echo 'Текст по ссылке '.$v['link_donor'].' - упрощён<br>';
            else echo '<span style="background-color:darkred">'.'Ошибка!!! Текст по ссылке '.$v['link_donor'].' - не упрощён</span><br>';
            
            //echo '!!!!!'.$paginator.'<br><br><br>'.$sql;

            //echo '<br><br><br><hr>'.$v['full_text_donor'].'<br>'.$v['paginator_full_text_remove'];

        }
        echo '<p>Следущий этап <a href="?removestyle">Очистить текст от стилей и атрибутов...</a></p>';
    }elseif(isset($_GET['removestyle'])){
        $sql='SELECT m.id, m.link_donor, m.full_text_donor, y.paginator_full_text_remove_attr
FROM sites_donor_link m LEFT JOIN sites_donor y ON m.id_site = y.id WHERE m.full_text_donor IS NOT NULL';
        $res=$DB->arrSQL($sql);
        foreach($res as $k=>$v){
            $remove_parts=explode('?#',$v['paginator_full_text_remove_attr']);
            $remove_attr=['style','class','id'];
            $cat_page=phpQuery::newDocument($v['full_text_donor']);

            foreach($remove_parts as $del_par){
                $paginator = $cat_page->find($del_par);
                foreach($paginator as $link){
                    foreach($remove_attr as $item)pq($link)->removeAttr($item);
                }
            }

            $paginator=$cat_page->contents();

            $sql='UPDATE sites_donor_link SET full_text_donor='.$DB->realEscapeStr($paginator).' WHERE id='.$v['id'];
            if($DB->boolSQL($sql))echo 'Атребуты по ссылке '.$v['link_donor'].' - упрощены<br>';
            else echo '<span style="background-color:darkred">'.'Ошибка!!! Атребуты по ссылке '.$v['link_donor'].' - не упрощены</span><br>';

            //echo '!!!!!'.$paginator.'<br><br><br>'.$sql;
            //echo '<br><br><br><hr>'.$v['full_text_donor'].'<br>'.$v['paginator_full_text_remove'];
        }
    }
}







///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);