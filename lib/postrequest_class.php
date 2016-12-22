<?php
class PostRequest{

    private $site;

    function __construct(){
        $this->site=$_SERVER['SERVER_NAME'];
    }

    public static function issetPostArr(){return(!empty($_POST))?true:false;}

    public static function issetPostKey($keys){
        $err=false;
        foreach($keys as $key){if(!array_key_exists($key,$_POST)){$err=true;}}
        return($err)?false:true;
    }

    public static function answerErrJson($return=false){
        $answer=['err'=>true];
        $answer['errText']=Validator::$ErrorForm;
        if($return){return $answer;}else{echo json_encode($answer);}
    }

    public static function feedback(){$err=false;
        if(PostRequest::issetPostKey(['name','mail','theme','sms','captcha'])){
            if(Validator::auditCaptcha($_POST['captcha'])){
                $name=Validator::auditFIO($_POST['name']);
                if(!$name){$err=true;}
                $mail=Validator::auditMail($_POST['mail']);
                if(!$mail){$err=true;}
                $theme=Validator::auditText($_POST['theme'],'тема');
                if(!$theme){$err=true;}
                $sms=Validator::auditTextArea($_POST['sms'],'сообщение');
                if(!$sms){$err=true;}
                if(!$err){//добавить в БД
                    $ip=Validator::getIp();
                    $DB=new SQLi();
                    $sql='SELECT id FROM feedback WHERE captcha=? AND readed IS NULL';
                    $sql=$DB->realEscape($sql,Validator::$captcha);
                    if($DB->intSQL($sql)!=''){
                        //Ошибка капчи
                        $err=true;
                        Validator::$ErrorForm[]='Не верно введена капча';
                    }else{
                        $sql='INSERT INTO feedback(captcha,name,mail,theme,text,ip,data)VALUES(?,?,?,?,?,?,?)';
                        $param=[Validator::$captcha,$name,$mail,$theme,$sms,$ip,time()];
                        $sql=$DB->realEscape($sql,$param);
                        if(!$DB->boolSQL($sql)){
                            $err=true;
                            Validator::$ErrorForm[]='Ошибка соединения, повторите попытку позже...';}
                    }
                }
            }else{$err=true;}
        }else{$err=true;}
        return($err)?false:true;
    }

    public function vkext(){$err=false;
        if(PostRequest::issetPostKey(['name','captcha','sms','idcomment'])){
            if(Validator::auditCaptcha($_POST['captcha'])){
                $name=Validator::auditText($_POST['name'],'имя',100);
                if(!$name){$err=true;}
                $sms=Validator::auditTextArea($_POST['sms'],'сообщение',10000);
                if(!$sms){$err=true;}
                $idcomment=Validator::html_cod($_POST['idcomment']);
                if(!Validator::paternIntMinus($idcomment)){$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют';}
                if(!$err){//добавить в БД
                    $ip=Validator::getIp();
                    $sqlTable=$this->sqlTableComment();
                    $refUrl=$this->refUrl();
                    $DB=new SQLi();
                    if($idcomment>0){
                        $idcomment=$DB->realEscapeStr($idcomment);
                        $res=$DB->strSQL('SELECT link FROM comment_uri WHERE id='.$idcomment);
                        if($res['link']==$refUrl){
                            $captcha=$DB->realEscapeStr(Validator::$captcha);
                            $res2=$DB->strSQL('SELECT id FROM '.$sqlTable.' WHERE readed IS NULL AND captcha='.$captcha);
                            if($res2){$err=true;Validator::$ErrorForm[]='Не верно введена капча';
                            }else{
                                if($DB->boolSQL($DB->realEscape('INSERT INTO '.$sqlTable.'(id_dir,captcha,user_name,text,ip,data)VALUES('.$idcomment.','.$captcha.',?,?,?,'.time().')',[$name,$sms,$ip]))){$err=false;
                                }else{$err=true;Validator::$ErrorForm[]='Ошибка запроса';}
                            }
                        }else{$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют!';}
                    }else{
                        $id_comment=$DB->realEscapeStr($idcomment*(-1));
                        $res=$DB->strSQL('SELECT id,table_name,id_link,link FROM comment_uri WHERE id_link='.$id_comment);
                        if($res){
                            if($res['link']==''){
                                $res2=$DB->strSQL('SELECT link,comment FROM '.$res['table_name'].' WHERE id='.$res['id_link']);
                                if($res2){
                                    $refUrlArr=$this->refUriParts($refUrl);
                                    $refUrlArrCount=count($refUrlArr);$refUrlArrCount--;
                                    $refUrlEnd=$refUrlArr[$refUrlArrCount];
                                    if($refUrlEnd==$res2['link'] && $res2['comment']<0){
                                        $refUrl=$DB->realEscapeStr($refUrl);
                                        $insLink=$DB->boolSQL('UPDATE comment_uri SET link='.$refUrl.' WHERE id='.$res['id']);
                                        if($insLink){

//???????????????????????????????????????????????????????????????????????????????????


                                            return $insLink.'+'.$refUrlEnd.$res2['comment'];
                                            //$insLink=$DB->boolSQL('UPDATE '.$res['table_name'].' SET comment=0');

                                        }else{$err=true;Validator::$ErrorForm[]='Повторите попытку позже!';}
                                    }else{$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют!!!!';}
                                }else{$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют!!!';}
                            }else{$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют!!';}
                        }else{$err=true;Validator::$ErrorForm[]='Коментарии отсутствуют!';}
                    }
                }
            }else{$err=true;}
        }else{$err=true;}
        //return($err)?false:true;
    }

    private function refUrl(){
        return parse_url(urldecode(Validator::html_cod($_SERVER['HTTP_REFERER'])),PHP_URL_PATH);}

    private function refUriParts($url=null){
        if(is_null($url))$url=$this->refUrl();
        return explode('/',trim($url,'/'));}

    private function sqlTableComment(){
        $x=count($this->refUriParts());
        return 'comment_counturi'.$x;
    }
}