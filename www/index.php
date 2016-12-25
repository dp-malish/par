<?php

$start = microtime(true);

set_include_path(get_include_path().PATH_SEPARATOR.'../lib'.PATH_SEPARATOR.'../lib_parse'.PATH_SEPARATOR.'../phpQuery');
spl_autoload_extensions("_class.php");spl_autoload_register();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Документ</title>
</head>

<body>
<form action="" method="post">
    <table>
        <tr>
            <td>Карта сайта:</td>
            <td>
                <select name="sitemap"><?php
                    $filelist=glob("../cache_all/parse/*.tmp");//../cache_all/parse/
                    //print_r($filelist);
                    foreach($filelist as $val){
                        $x=explode('/',$val);
                        echo '<option value="'.$x[3].'">'.$x[3].'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit"></td>
        </tr>


    </table>
</form><?php

if(isset($_POST['sitemap'])){
    /*$html=new Curl();
    echo $html->connectLow($_POST['url']);*/

    $s_map= new ParseSitemap();
    $url=$s_map->getUrl($_POST['sitemap']);
    echo $url;

}

$time = microtime(true) - $start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.', $time);
?>
</body>
</html>
