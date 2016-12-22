<?php
class Validator {

	public static $ErrorForm=[];
	public static $captcha=null;

	//Первым делом применять к входящим данным
	public static function html_cod($str){
	$res=htmlspecialchars($str, ENT_QUOTES);
	$res=trim($res);
	return $res;
	}
	public static function issetCookie($str){
		return(isset($_COOKIE[$str]))?self::html_cod($_COOKIE[$str]):false;
	}
	public static function getIp(){
		return self::html_cod($_SERVER['REMOTE_ADDR']);
	}
	//**************************	
	
	/*Длинна строки. Параметр: текст, заданная длинна
	Если $dlina=0 возвращает длинну строки иначе
	проверка на пустую строку(0) или превышающую $dlina (2)
	Если стр в допуске возвращает (1)*/
	public static function LengthStr($str,$dlina){
	if($str==''){$res=0;}else{
		$res=mb_strlen($str,'UTF-8');
		if($dlina>0){$res <= $dlina ? $res=1 : $res=2;}
	}//Если стр пуста
	return $res;
	}
	//**************************
	//preg_match  true:хорошо  false:плохо
	public static function paternInt($str){
		return(preg_match("/^[0-9]+$/u",$str))?true:false;
	}
	public static function paternIntMinus($str){
		return(preg_match("/^[0-9\-]+$/u",$str))?true:false;
	}
	public static function paternStrLink($str){
		return(preg_match("/^[0-9А-Яа-яЁёa-zA-Z_\-]+$/u",$str))?true:false;
	}
	public static function paternStrRusText($str){
		return(preg_match("/^[0-9А-Яа-яЁёa-zA-Z_\-\n\s\(\)\.,!?:;]+$/u",$str))?true:false;
	}

	//**************************
	public static function auditFIO($str){
		$str=Validator::html_cod($str);
		$l=self::LengthStr($str,130);
		if($l==0){self::$ErrorForm[]='Незаполненное поле ФИО';return false;}
		elseif($l==2){self::$ErrorForm[]='Максимальная длина поля ФИО - 100 символов';return false;}
		else{
			if(self::paternStrRusText($str)){return	$str;}else{
				self::$ErrorForm[]='В поле ФИО используются недопустимые символы';
				return false;
			}
		}
	}
	public static function auditMail($str){
		$str=Validator::html_cod($str);
		$l=self::LengthStr($str,130);
		if($l==0){self::$ErrorForm[]='Неверно заполненно поле email';return false;}
		elseif($l==2){self::$ErrorForm[]='Максимальная длина поля email - 100 символов';return false;}
		else{
			$str=mb_strtolower($str);
			if(mb_substr_count($str,'@','UTF-8')==1){return $str;}else{
				self::$ErrorForm[]='Не корректно заполненно поле email';
				return false;
			}
		}
	}
	public static function auditText($str,$errField,$dlina=130){
		$str=Validator::html_cod($str);
		$l=self::LengthStr($str,$dlina);
		$printDlina=$dlina-30;
		if($l==0){self::$ErrorForm[]='Незаполненное поле '.$errField;return false;}
		elseif($l==2){self::$ErrorForm[]='Максимальная длина поля '.$errField.' - '.$printDlina.' символов';return false;}
		else{
			if(self::paternStrRusText($str)){return $str;}else{
				self::$ErrorForm[]='В поле '.$errField.' используются недопустимые символы';
				return false;
			}
		}
	}
	public static function auditTextArea($str,$errField,$dlina=1030){
		$str=Validator::html_cod($str);
		$l=self::LengthStr($str,$dlina);
		$printDlina=$dlina-30;
		if($l==0){self::$ErrorForm[]='Незаполненное поле '.$errField;return false;}
		elseif($l==2){self::$ErrorForm[]='Максимальная длина поля '.$errField.' - '.$printDlina.' символов';return false;}
		else{return	$str;}
	}
	public static function auditCaptcha($str){

		$err=false;
		$captcha=null;

		$str=self::html_cod($str);
		if(self::paternInt($str)){
			self::$captcha=$str;
			$captcha=Captcha::dp_md_hash($str);
		}else{$err=true;}

		$cook=self::issetCookie('dp_ses');
		if(!$cook){$err=true;}elseif($captcha!=$cook){$err=true;}

		if($err){self::$ErrorForm[]='Не верно введена капча';}

		return(!$err)?true:false;
	}
}