<?php
$start=microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br><br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);

ini_set('max_execution_time','18000000'); //время выполнения скрипта

set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();

if(empty($_GET)){
    echo '<ul>
        <li><a href="?getlink">Установить ссылку</a></li>
        <li><a href="?copyimg">Скопировать изображения</a></li>
        <li><a href="?copyimgtodb">Скопировать изображения в БД</a></li>        
        </ul>';
}else{
    $DB = new SQLi();

    if(isset($_GET['getlink'])){
        $sql='SELECT id,link_donor,full_text_donor FROM sites_donor_link WHERE img_donor IS NULL limit 1';
        $res=$DB->arrSQL($sql);
        if($res){
            foreach($res as $k=>$v){
                $cat_page = phpQuery::newDocument($v['full_text_donor']);
                
                $img_link='';

                $paginator=$cat_page->find('img');
                foreach($paginator as $link){
                    $x=pq($link)->attr('src');
                    $img_link.=($img_link!=''?'?#':'').$x;
                }

                $sql='UPDATE sites_donor_link SET img_donor='.$DB->realEscapeStr($img_link).' WHERE id='.$v['id'];
                echo $sql;
                //echo 'Запись №' . $v['id'] . ' ссылка ' . $v['link_donor'] . ' каталог изображения - ' . (($DB->boolSQL($sql)) ? 'добавлен' : '<span style="background-color:darkred">ошибка</span>') . '<br>';
            }
            //echo 'Каталоги для изображений обновлены<br>';
        }
        echo '<p>Следущий этап <a href="?copyimg">Скопировать изображения</a></p>';
    }elseif(isset($_GET['copyimg'])){
        $sql='SELECT id,site,img_s_donor,img_s_table,img_s_dir FROM sites_donor_link WHERE img_s_name IS NULL';
        $res=$DB->arrSQL($sql);
        if($res){
            foreach($res as $k=>$v){

                $path=__DIR__.$v['img_s_dir'];
                if(!file_exists($path))mkdir($path,0777,true);

                $uri_parts=explode('/',$v['img_s_donor']);
                $count_uri_parts=count($uri_parts);
                $file_name=$uri_parts[$count_uri_parts-1];

                if(!copy($v['img_s_donor'],$path.$file_name)){
                    echo '<span style="background-color:darkred">ошибка</span>'.'не удалось скопировать '.$v['img_s_donor'];
                }else{
                    if($DB->boolSQL('UPDATE sites_donor_link SET img_s_name='.$DB->realEscapeStr($file_name).' WHERE id='.$v['id'])){
                        echo 'Добавлено изображение '.$path.$file_name.'<br>';
                    }else{
                        echo '<span style="background-color:darkred">Ошибка</span> изображения '.$path.$file_name.'<br>';
                    }
                }
            }
        }
        echo '<p>Следущий этап <a href="?copyimgtodb">Скопировать изображения в БД</a></p>';
    }elseif(isset($_GET['copyimgtodb'])){

        $sql='SELECT DISTINCT img_s_table FROM sites_donor_link WHERE img_s_name IS NOT NULL AND img_s IS NULL';
        $res=$DB->arrSQL($sql);

        foreach($res as $k=>$v){
            if($DB->boolSQL('CREATE TABLE IF NOT EXISTS '.$v['img_s_table'].'(id int(11) NOT NULL AUTO_INCREMENT,name_file varchar(255) NOT NULL,png tinyint(1) DEFAULT NULL,content longblob NOT NULL,PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;')){
                echo 'Таблица для изображений '.$v['img_s_table'].' создана...<br>';
            }else{
                echo '<span style="background-color:darkred">Таблица для изображений '.$v['img_s_table'].' не создана...</span><br>';
            }
        }echo '<br><br>';

        $sql='SELECT id,site,img_s_table,img_s_dir,img_s_name FROM sites_donor_link WHERE img_s_name IS NOT NULL AND img_s IS NULL';
        $res=$DB->arrSQL($sql);
        if($res){
            $img=new Par_img();
            foreach($res as $k=>$v){
                $id_img=$img->putImgToDB(__DIR__.$v['img_s_dir'].$v['img_s_name'],$v['img_s_table'],$v['img_s_name']);
                if($id_img){

                    if($DB->boolSQL('UPDATE sites_donor_link SET img_s='.$id_img.' WHERE id='.$v['id']))
                    echo $id_img.' - '.$v['img_s_name'].'<br>';
                    else echo '<br>Изображение без id  - '.$v['img_s_name'].' - проведите редактирование...<br><br>';

                }else{echo Validator::$ErrorForm[0];}
            }
        }else echo 'Нет не заполненных изображений<br>';
    }
}



//////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);