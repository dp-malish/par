<?php
class SQL_obj extends mysqli{
    private $host='localhost';
    private $user='root';
    private $pass='root';
    private $db_name='malish';
    private $charset='utf8';
    public function __construct($host='',$user='',$pass='',$db_name='',$charset='',$port=NULL,$socket=NULL){
        if(strlen($host)>0)$this->host=$host;
        if(strlen($user)>0)$this->user=$user;
        if(strlen($pass)>0)$this->pass=$pass;
        if(strlen($db_name)>0)$this->db_name=$db_name;
        if(strlen($charset)>0)$this->charset=$charset;
        parent::__construct($this->host, $this->user, $this->pass, $this->db_name, $port, $socket);
        if(!$this->connect_error){
            $this->set_charset($this->charset);
        }
    }
    public function boolSQL($sql){
        if($this->query($sql)===TRUE){return 1;}else{return 0;}
    }
    public function intSQL($sql){
        $result=$this->query($sql);
        $row = $result->fetch_row();
        return $row[0];
        //$result->close();
    }
    public function strSQL($sql){
        $result=$this->query($sql);
        $res=$result->fetch_array(MYSQLI_ASSOC);
        $result->close();
        return $res;
    }
    public function arrSQL($sql){
        $columns = array();
        $result=$this->query($sql);
        while($res=$result->fetch_array(MYSQLI_ASSOC)){$columns[]=$res;}
        $result->free();
        return $columns;
    }
    public function __destruct(){$this->close();}
}