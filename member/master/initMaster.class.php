<?php
/*
ファイルパス：C:\xampp\htdocs\member\master\initMaster.class.php
ファイル名：initMaster.class.php
*/

namespace member\master;

class initMaster
{
	public function __construct()
	{

	}

	public static function getDate()
	{
		$yearArr = [];
		$monthArr = [];
		$dayArr = [];

	$next_year = date('Y') + 1;//dateで今の「年」を取ってくる

	//年を作成
	for ($i = 1900; $i < $next_year; $i ++) {
		$year = sprintf("%04d", $i);//4桁の数字で返す
		$yearArr[$year] = $year . '年';
	}//          ↑key      ↑value

	//月を作成
	for ($i = 1; $i < 13; $i ++) {
		$month = sprintf("%02d", $i);//1桁でも01とかになる
		$monthArr[$month] = $month . '月';
	}

	//日を作成
	for ($i = 1; $i < 32; $i ++) {
		$day = sprintf("%02d", $i);
		$dayArr[$day] = $day . '日';
	}

	return [$yearArr, $monthArr, $dayArr];//呼び出し元に返す(initMaster::getDate();)
	}

	public static function getSex()
	{
		$sexArr = ['1' => '男性', '2' => '女性'];
		return $sexArr;
	}

	public static function getTrafficWay()
	{
		$trafficArr = ['徒歩', '自転車', 'バス', '電車', '車・バイク'];
		return $trafficArr;
	}
}

