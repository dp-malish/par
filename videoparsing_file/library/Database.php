<?php
/*
Класс для работы с БД
Все функции для вытаскивания и записи даных в БД
*/

if ( ! defined('BASEPATH')) exit('Прямой доступ к файлу запрещен!');

final class Database { //final - значит нельзя этот класс наследовать.
  	const VERSION = 1.02;
	public $isLogging = TRUE;
	
	public $db;
	
	public function __construct($host,$user,$pass,$db) {
		$this->db = mysql_connect($host,$user,$pass);
		
		
		if(!$this->db) exit('Нет соединения с базой данных');		
		if(!mysql_select_db($db,$this->db)) exit('Нет таблицы');
		
		
		mysql_query("SET NAMES utf-8");
		
		mysql_query("set character_set_client='utf8'");
		mysql_query("set character_set_results='utf8'");
		mysql_query("set collation_connection='utf8_unicode_ci'");
		
		return $this->db;
	}	
	
	private function cleanInput($input) {
		  $search = array(
			'@<script[^>]*?>.*?</script>@si',   // javascript
			'@<[\/\!]*?[^<>]*?>@si',            // HTML теги
			'@<style[^>]*?>.*?</style>@siU',    // теги style
			'@<![\s\S]*?--[ \t\n\r]*>@'         // многоуровневые комментарии
		  );
		
			$output = preg_replace($search, '', $input);
			return $output;
	}
	
	//полная очистка данных	  
	//обязательно все входящие переменные чистим на всякий случай
	private function sanitize($input) {
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = $this->sanitize($val);
			}
		} else {
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = mysql_real_escape_string($input);
		}
		return $output;
	}
	
	//для очистки данных в тех случаях когда нужно оставить html теги
	private function cleantiny($input) {

	  $search = array(
		'@<script[^>]*?>.*?</script>@si',   // javascript   
		'@<style[^>]*?>.*?</style>@siU',    // теги style
		'@<![\s\S]*?--[ \t\n\r]*>@'         // многоуровневые комментарии
	  );
	
		$output = preg_replace($search, '', $input);
		return $output;
	}
	
	//получить все записи таблицы
	public function query_all($query) {	
		$query=$this->sanitize($query);		
		$res = mysql_query($query);
		
		if(!$res) return FALSE;
		$row=array();
		for ($i = 0;$i < mysql_num_rows($res); $i++) 
			$row[] = mysql_fetch_array($res,MYSQL_ASSOC);		
		return $row;
	}
	
	//получить запись
	public function query_one($query) {
		
		$query=$this->sanitize($query);	
		
		$res = mysql_query($query);
		
		if(!$res) return FALSE;		
		$row = mysql_fetch_array($res,MYSQL_ASSOC);		
		return $row;
	}
	
	//получить все записи таблицы
	public function get_all($table) {
		
		$table=$this->sanitize($table);
		
		$sql = "SELECT * FROM $table";
		
		$res = mysql_query($sql);
		
		if(!$res) return FALSE;
		$row=array();
		for ($i = 0;$i < mysql_num_rows($res); $i++) 
			$row[] = mysql_fetch_array($res,MYSQL_ASSOC);
	
		
		return $row;
	}
	
	//получить запись
	public function get_one($id, $table) {
		$table=$this->sanitize($table);
		$id=$this->sanitize($id);
		
		$sql = "SELECT * FROM $table WHERE id='$id'";
		$res = mysql_query($sql);
		
		if(!$res) return FALSE;		
		$row = mysql_fetch_array($res,MYSQL_ASSOC);		
		return $row;
	}
	
	//получить запись по параметру
	public function get_all_by_param($param, $value, $table) {
		$table=$this->sanitize($table);
		$param=$this->sanitize($param);
		$value=$this->sanitize($value);
		
		$sql="SELECT * FROM $table WHERE  $param='$value'";
		$res = mysql_query($sql);		
		if(!$res) return FALSE;	
		
		$row=array();
		for ($i = 0;$i < mysql_num_rows($res); $i++)
			$row[] = mysql_fetch_array($res,MYSQL_ASSOC);	
					
		return $row;
	}
	
	//получить данные обьекта по id
	public function get_data($id, $data, $table) {
		$table=$this->sanitize($table);
		$data=$this->sanitize($data);
		$id=$this->sanitize($id);
		
		 $ResultQuery=mysql_query ("SELECT 
                                            *
                                        FROM
                                            $table 				  	
                                       WHERE 
                                            id ='$id'");

          while($Result=mysql_fetch_array($ResultQuery))	
			return $Result[$data];			
		return 0; // если не одного не найдено       
	}
	
	//получить запись по параметрам
	public function get_one_by_params($array_params, $table) {
		$table=$this->sanitize($table);
		$sql="SELECT * FROM $table WHERE "; 	
		
		$count_params=count($array_params);	
		$i=0;	
		foreach($array_params as $param=>$val){
			 $i++;			 
			 if ($i!==$count_params) $and=" AND "; else $and=""; 
			 
			  //тщательно чистим переменные
			 $param=$this->sanitize($param);
			 $val=$this->sanitize($val);
			 
			 $sql.=" (".$param."='".$val."')".$and;			 
		}
		
		$res = mysql_query($sql);	
		if(!$res) return FALSE;				 
		$row = mysql_fetch_array($res,MYSQL_ASSOC);	
		return $row;
	}
	
	
	//получить записи по параметрам
	public function get_all_by_params($array_params, $table) {
		$table=$this->sanitize($table);
		$sql="SELECT * FROM $table WHERE "; 	
		
		$count_params=count($array_params);	
		$i=0;	
		foreach($array_params as $param=>$val){
			 $i++;			 
			 if ($i!==$count_params) $and=" AND "; else $and=""; 
			 
			  //тщательно чистим переменные
			 $param=$this->sanitize($param);
			 $val=$this->sanitize($val);
			 
			 $sql.=" ($param='$val')".$and;			 
		}
				
		$res = mysql_query($sql);
		
		if(!$res) return FALSE;
		$row=array();
		for ($i = 0;$i < mysql_num_rows($res); $i++) 
			$row[] = mysql_fetch_array($res,MYSQL_ASSOC);
	
		
		return $row;	
	}
	
	/*
	вставляем запись в БД с любой структурой	
	$params['name']="test"; //массив с именем поля и значением которое вставляем
	$db->insert($params,'category');	
	*/
	public function insert($array_params, $table) {
		$table=$this->sanitize($table);
		$sql="
			  INSERT INTO 
				$table 
			  SET
			  "; 	
				
		$count_params=count($array_params);	
		$i=0;	
		foreach($array_params as $param=>$val){
			 $i++;			 
			 if ($i!==$count_params) $and=" , "; else $and=""; 
			 
			 //тщательно чистим переменные
			 $param=$this->sanitize($param);
			 $val=$this->sanitize($val);
			 
			 $sql.=" $param='$val' ".$and;			 
		}
		return mysql_query($sql)  or die("<br>Ошибка при добавлении в таблицу <strong>$table</strong>:<br>". mysql_error()."<br>");
	}
	
	/*
	обновляем запись в БД 	
	$params['name']="Спортивные товары1";
	$filters['id']=1;	
	$db->update($params, $filters, 'category');
	*/
	public function update($array_params, $array_filter, $table) {
		$table=$this->sanitize($table);
		
		$sql="
			  UPDATE
				$table 
			  SET
			  "; 			  
			  						
		$count_params=count($array_params);	
		$i=0;	
		foreach($array_params as $param=>$val){
			 $i++;			 
			 if ($i!==$count_params) $and=" , "; else $and=""; 
			 
			 //тщательно чистим переменные
			 $param=$this->sanitize($param);
			 $val=$this->sanitize($val);
			 
			 $sql.=" $param='$val' ".$and;			 
		}
		
		$sql.=" WHERE ";
		
		$count_filter=count($array_filter);	
		$i=0;	
		foreach($array_filter as $param=>$val){
			 $i++;			 
			 if ($i!==$count_filter) $and=" , "; else $and=""; 
			 
			 //тщательно чистим переменные
			 $param=$this->sanitize($param);
			 $val=$this->sanitize($val);
			 
			 $sql.=" $param='$val' ".$and;			 
		}
		
		return mysql_query($sql)  or die("<br>Ошибка при изменении в таблицы <strong>$table</strong>:<br>". mysql_error()."<br>");
	}
	
	/*
	id последней вставленной записи
	*/
	public function last() {
		$sql="SELECT LAST_INSERT_ID()";
		$r = mysql_query($sql);
		while ($Result=mysql_fetch_array($r)) return $Result[0]; 
	}
	
	
} //class