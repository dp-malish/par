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
            <td>Имя файла:</td>
            <td><input type="text" name="filename" value="index.xml"></td>
        </tr>
        <tr>
            <td>Карта сайта:</td>
            <td>
                <input type="text" name="url" value="http://domosedkam.ru/sitemap_addl.xml.gz">
                <!--<input type="text" name="url" value="http://dp-malish.com/def.xml">-->
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit"></td>
        </tr>


    </table>
</form><?php

if(isset($_POST['url'])){
    $s_map= new ParseSitemap();
    $s_map->gzip_or_xml($_POST['url']);
}




$time = microtime(true) - $start;
printf("<br>".date('H:i:s').' Готово! Процесс выполнялся %.4F сек.', $time);
?>
</body>
</html>
