<?php
class Route{
    public static function location($uri=null){
        $site=$_SERVER['SERVER_NAME'];
        if(!is_null($uri))$site.=$uri;
        header('Location: http://'.$site);
        exit;
    }
    public static function modul404($uri=null){
        header("HTTP/1.0 404 Not Found");/*header("Status: 404 Not Found");*/
        self::location($uri);
    }
    public static function errPage(){
        $main_content='<section><h2>Новости</h2><div class="fon"><div class="text_article align-center">Вы пытаетесь загрузить несуществующую страницу.<br>Пожалуйста, убедитесь, что ссылка указанна правильно!<br><a href="/news">Последуйте к перечню новостей...</a></div></div></section><script type="text/javascript">setTimeout(\'location.replace("/news")\', 5000);</script>';
        return $main_content;
    }
}