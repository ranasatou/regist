<?php
/*
ファイルパス：C:\xampp\htdocs\member\lib\Database.class.php
ファイル名：Database.class.php
*/

namespace member\lib;

class Database
{
	public $db_con = NULL;
	public $db_host = '';
	public $db_user = '';
	public $db_pass = '';
	public $db_name = '';

	//__construct:アンダースコアは2つ
	
	public function __construct($db_host, $db_user, $db_pass, $db_name)
	{//bootstrapの定数がわたされる
		$this -> db_con = $this -> connectDB($db_host, $db_user, $db_pass, $db_name);
		$this -> db_host = $db_host;
		$this -> db_user = $db_user;
		$this -> db_pass = $db_pass;
		$this -> db_name = $db_name;
	}//「引数」で渡された$○○を代入　ここではbootstrapで定義されたもの

	private function connectDB($db_host, $db_user, $db_pass, $db_name)
	{
		$tmp_con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
		if($tmp_con !== false) {
			return $tmp_con;//メゾットの呼び出し元に値を返す
	}else{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();//printf ほぼechoと同じだが()の中にフォーマット書ける
	}
}

	public function execute($sql)
	{
		return mysqli_query($this -> db_con, $sql);
	}

	public function select($sql)
	{
		$res = $this -> execute($sql);
		$data = [];
		while ($row = mysqli_fetch_array($res)) {
			array_push($data, $row);//配列に配列を突っ込む
		}
		//mysql_free_result($res);
		return $data;
	}

	public function quote($int)
	{
		return mysqli_real_escape_string($this -> db_con, $int);
	}

	public function str_quote($str)
	{
		return "'" . mysqli_real_escape_string($this -> db_con, $str) . "'";
	}

	public function getLastId()
	{
		return mysqli_insert_id($this -> db_con);
	}

	public function close()
	{
		mysqli_close($this -> db_con);
	}
}

