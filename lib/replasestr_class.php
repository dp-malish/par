<?php
class ReplaseStr{public static function ReplaseEnt($str){$str=nl2br($str,false);$str=str_replace("\n",'',$str);$str=str_replace("\r",'',$str);return $str;}}