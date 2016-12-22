<?php
class Str_navigation{
    public static $navigation=null;public static $start_nav=1;
    public static function navigation($uri,$table_name,$page=1,$msg=13,$fon=false){
        $res=SQListatic::intSQL_('SELECT COUNT(id) FROM '.$table_name);
        if($res){$total=(int)(($res-1)/$msg)+1;
        self::$start_nav=$page*$msg-$msg;
        //Стрелки назад
        if($page!=1)$pervpage='<a href="/'.$uri.'/" title="Перейти на первую страницу">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;<a href="/'.$uri.'/'.($page-1!=1?$page-1:'').'">&lt;</a>&nbsp;&nbsp;';
        //Стрелки вперед
        if($page!=$total)$nextpage='&nbsp;&nbsp;<a href="/'.$uri.'/'.($page+1).'">&gt;</a>&nbsp;&nbsp;&nbsp;<a href="/'.$uri.'/'.$total.'" title="Перейти на последнюю страницу">&gt;&gt;</a>';
        //Находим две ближайшие станицы с обоих краев, если они есть
        for($i=1;$i<6;$i++){if($page-$i==1)$pageoneleft='<a href="/'.$uri.'/">1</a> | ';}
        if($page-4 >1)$page4left='<a href="/'.$uri.'/'.($page-4).'">'.($page-4).'</a> | ';
        if($page-3 >1)$page3left='<a href="/'.$uri.'/'.($page-3).'">'.($page-3).'</a> | ';
        if($page-2 >1)$page2left='<a href="/'.$uri.'/'.($page-2).'">'.($page-2).'</a> | ';
        if($page-1 >1)$page1left='<a href="/'.$uri.'/'.($page-1).'">'.($page-1).'</a> | ';
        if($page+4 <=$total)$page4right=' | <a href="/'.$uri.'/'.($page+4).'">'.($page + 4).'</a>';
        if($page+3 <=$total)$page3right=' | <a href="/'.$uri.'/'.($page+3).'">'.($page + 3).'</a>';
        if($page+2 <=$total)$page2right=' | <a href="/'.$uri.'/'.($page+2).'">'.($page+2).'</a>';
        if($page+1 <=$total)$page1right=' | <a href="/'.$uri.'/'.($page+1).'">'.($page+1).'</a>';
        $fon_print=($fon?'fon_c ':'');
        if($total>1 && $total>=$page){self::$navigation='<div class="'.$fon_print.'ac nav_link"><p>'.$pervpage.$pageoneleft.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$nextpage.'</p></div>';}
    }}
}