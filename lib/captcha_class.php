<?php
class Captcha{
	const WIDTH=100;
	const HEIGHT=60;
	const FONT_SIZE=36;

	const R_FON=180;
	const G_FONT=180;
	const B_FONT=180;
	const DEF_FONT='/font/Rosamunda Two.ttf';

	function __construct(){
		$rnd=rand(10000,99999);
		$this->setCookie($this->dp_md_hash($rnd));
		$this->genInt($rnd.'');
	}

	public static function dp_md_hash($hash_val){
		$hash_val=md5($hash_val.Opt::COOKIE_SALT);
		return md5($hash_val);
	}

	private function setCookie($val){
		setcookie('dp_ses',$val,time()+2500000,'/','.'.$_SERVER['SERVER_NAME']);
	}

	public function genInt($text){
		header('Content-type: image/png');
		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0,pre-check=0", false);
		header("Cache-Control: max-age=0", false);
		header("Pragma: no-cache");

		$font=$_SERVER['DOCUMENT_ROOT'].self::DEF_FONT;
		$img=imagecreatetruecolor(self::WIDTH, self::HEIGHT);//создаёт новое изображение

		$cur_color=imagecolorallocate($img, self::R_FON, self::G_FONT, self::B_FONT);
		imagefill($img,0,0,$cur_color);
		imagesavealpha($img,true);
// allocate some colors
		$red=imagecolorallocatealpha($img,255,0,0,50);
		$green=imagecolorallocate($img,0,255,0);
		$blue=imagecolorallocate($img,0,0,255);
//лицо
		imagearc($img, 50, 30, 57, 57, 0, 360, $red);
		imagearc($img, 38, 17,  18,  18,  0, 360, $blue);
		imagearc($img, 62, 17,  18,  18,  0, 360, $blue);
		imagearc($img, 38, 17,  5,  5,  0, 360, $green);
		imagearc($img, 62, 17,  5,  5,  0, 360, $green);
		imageline($img, 50, 25, 50, 40, $green);
		imagearc($img, 50, 40, 18, 18, 25, 155, $red);
//лицо конец
		$cur_color = imagecolorallocatealpha( $img, rand(0, 150), rand(0, 150), rand(0, 150), rand(15,100));
		imagearc($img, 40, 8, 140, 35, 45, 185, $cur_color);
		$cur_color = imagecolorallocatealpha( $img, rand(0, 150), rand(0, 150), rand(0, 150), rand(15,100));
		imagearc($img, 40, 15, 140, 40, 45, 135, $cur_color);
		$cur_color = imagecolorallocatealpha( $img, rand(0, 150), rand(0, 150), rand(0, 150), rand(15,100));
		imagearc($img, 40, 23, 140, 35, 45, 185, $cur_color);

		$caplen=mb_strlen($text);
		for($i=0;$i<$caplen;$i++){
			//$cur_color = imagecolorallocate( $img, rand(0, 150), rand(0, 150), rand(0, 150) );//цвет для текущей буквы
			//imagearc($img, rand(2, self::HEIGHT), rand(2,self::WIDTH-15), 140, 40, 45, 185, $cur_color);
			$angle=rand(-15,13);//случайный угол наклона

			$x=(self::WIDTH-23)/$caplen*$i+12;//растояние между символами
			$x=rand($x,$x+4);//случайное смещение

			$y=self::HEIGHT-((self::HEIGHT-self::FONT_SIZE)/1.5);// координата Y
			$cur_color = imagecolorallocate( $img, rand(0, 100), rand(0, 100), rand(0, 100) );//цвет для текущей буквы

			imagettftext($img,self::FONT_SIZE,$angle,$x,$y,$cur_color,$font,$text{$i});//вывод текста
		}
		imagepng($img);
		imagedestroy($img);
	}
}