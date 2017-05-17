<?php

/**
 * Created by PhpStorm.
 * User: WinTeh
 * Date: 21.12.2016
 */
class Curl{

    public function connectLow($url){
        $url=Validator::html_cod($url);
        $url=curl_init($url);

        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, 0);// не проверять SSL сертификат
        curl_setopt($url, CURLOPT_SSL_VERIFYHOST, 0);// не проверять Host SSL сертификата

        curl_setopt($url, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($url, CURLOPT_REFERER, 'http://www.google.com/'); //делаем вид что перешли из google

        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($url, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36 OPR/42.0.2393.85');
        //curl_setopt($url, CURLOPT_HEADER, 1); //выводим заголовок чтобы по нему определить авторизовались или нет

        $res=curl_exec($url);//выполняем curl с указанными выше настройками
        if(curl_errno($url)){//если есть ошибки выводим их
            print curl_error($url);
            exit;
        }
        curl_close($url);
        return $res;
    }

}