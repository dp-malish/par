<?php
/**
 * User: Пикс
 * Date: 19.05.2017
 */
class Par_img{

    public function __construct(){
        echo 'Img class start...<br>';
    }

    function putImgToDB($file,$table,$file_name=''){
        $imgInfo=getimagesize($file);
        if($imgInfo['mime']=='image/png'){$png=1;
        }elseif($imgInfo['mime']=='image/jpeg'){$png='NULL';
        }else{Validator::$ErrorForm[]='Не доустимый формат изображения';}

        if(empty(Validator::$ErrorForm)){
            $DB=new SQLi();

            $file_name=$DB->realEscapeStr($file_name);
            $content=$DB->realEscapeStr(file_get_contents($file));

            if($DB->boolSQL('INSERT INTO '.$table.' VALUES(NULL,'.$file_name.','.$png.','.$content.');'))
                return $DB->lastId();
            else{Validator::$ErrorForm[]='Ошибка БД'; return false;}
        }else return false;
    }




    
}