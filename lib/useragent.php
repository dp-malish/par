<?php
class UserAgent{
    public static $HTTP_USER_AGENT;
    public static $isBot=false;
    function __construct(){self::$HTTP_USER_AGENT=Validator::html_cod($_SERVER['HTTP_USER_AGENT']);}

    public function insertUserAgent($DB){//переработать иначе двоятся записи
        if(!is_null(SQLi::$mysql_link)){
            $ip=$_SERVER['REMOTE_ADDR'];
            $sql='INSERT INTO bot VALUES(NULL,?,\''.$ip.'\',?)';
            $sql=$DB->realEscape($sql,[self::$HTTP_USER_AGENT,time()]);
            $DB->boolSQL($sql);
        }
    }
    public function isBot(){
        $bots=['rambler','googlebot','mediapartners','aport','yahoo','msnbot','mail.ru',
            'yetibot','ask.com','liveinternet.ru','yandexbot','google page speed','bing.com'];
        foreach($bots as $bot){if(mb_stripos(self::$HTTP_USER_AGENT,$bot)!==false){self::$isBot=true;}}
        return self::$isBot;
    }

    public function isMobile(){
        if(isset($_COOKIE['mob'])){
            $mob=htmlspecialchars($_COOKIE['mob'],ENT_QUOTES);
            if(!preg_match("/[^0-1]+/",$mob)){return($mob)?true:false;}else{exit;}
        }else{return $this->mobileDetect();}
    }
    private function mobileDetect(){$mob=0;
        $mob_agent=['ipad','iphone','android','pocket','palm','windows ce','windowsce','cellphone','opera mobi','ipod','small','sharp','sonyericsson','symbian','opera mini','nokia','htc_','samsung','motorola','smartphone','blackberry','playstation portable','tablet browser'];
        $agent=strtolower(self::$HTTP_USER_AGENT);
        foreach($mob_agent as $v){if(strpos($agent,$v)!==false){$mob=1;}}
        setcookie('mob',$mob, time()+28144000,'/','.'.$_SERVER['SERVER_NAME']);
        return $mob;
    }
}