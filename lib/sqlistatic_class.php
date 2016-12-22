<?php
class SQListatic extends SQLi{

    public static function boolSQL_($sql){
        if(self::connectDB()){
            if(mysqli_query(self::$mysql_link,$sql)===TRUE){return true;
            }else{return false;}
        }else{return false;}
    }
    public static function intSQL_($sql){
        if(self::connectDB()){
            $result=mysqli_query(self::$mysql_link,$sql);
            $row=$result->fetch_row();
            return $row[0];
        }else{return false;}
    }
    public static function arrSQL_($sql){if(self::connectDB()){$columns=[];$result=mysqli_query(self::$mysql_link,$sql);
        while($res=$result->fetch_array(MYSQLI_ASSOC)){$columns[]=$res;}$result->free();return $columns;}else{return false;}
    }
}
