<?php
class Data{
    public static function IntToStrMap($int){
        return date('Y-m-d',$int);
    }
    public static function IntToStrDate($int){
        return date('d-m-Y',$int);
    }
    public static function IntToStrDateTime($int){
        return date('H:i:s d-m-Y',$int);
    }
    public static function StrDateTimeToInt($str){
        return strtotime($str);//"2006-07-31 22:45:59"
    }

    public static function DatePass(){
        $d=date('j');
        $m=date('n');
        return $d+$m;
    }
}
