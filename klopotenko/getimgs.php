<?php
/**
 * User: Пикс
 * Date: 24.05.2017
 */
$start = microtime(true);
echo date('H:i:s').' Начинаем парсинг...<br><br>';
$site=$_SERVER['SERVER_NAME'];$root=$_SERVER['DOCUMENT_ROOT'];
Error_Reporting(E_ALL & ~E_NOTICE);ini_set('display_errors',1);
set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_register();





if(empty($_GET)){

    echo '<ul>
        <li><a href="?setdir">Установить каталог</a></li>
        <li><a href="?copyimg">Скопировать изображения</a></li>
        
        </ul>';

}else {
    $DB = new SQLi();

    if(isset($_GET{'setdir'})){
        $sql='SELECT m.id,m.link_donor,m.img_s_donor,y.img_s_dir,y.img_s_table FROM sites_donor_link m
LEFT JOIN sites_donor_options  y ON m.id_opt = y.id WHERE y.img_s_dir IS NOT NULL';
        $res=$DB->arrSQL($sql);
        if($res){
            foreach ($res as $k => $v) {
                $path = realpath(__DIR__) . $v['img_s_dir'];
                //echo $path . '<br>';
                if (!file_exists($path)) mkdir($path, 0777, true);
                $sql = 'UPDATE sites_donor_link SET img_s_dir="' . $v['img_s_dir'] . '", img_s_table="' . $v['img_s_table'] . '" WHERE img_s_dir IS NULL AND id=' . $v['id'];

                echo 'Запись №' . $v['id'] . ' ссылка ' . $v['link_donor'] . ' каталог изображения - ' . (($DB->boolSQL($sql)) ? 'добавлен' : '<span style="background-color:darkred">ошибка</span>') . '<br>';
            }
            echo 'Каталоги для изображений обновлены<br>';
        }
    }elseif(isset($_GET{'copyimg'})){

        echo '8';




    }



}







///////////////////////
$time=microtime(true)-$start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.',$time);