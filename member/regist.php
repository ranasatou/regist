<?php
/*
ファイルパス：C:\xampp\htdocs\member\regist.php
ファイル名：regist.php
アクセスURL:http://localhost/member/regist.php
*/
namespace member;


require_once dirname(__FILE__) . '\Bootstrap.class.php';

use member\master\initMaster;
use member\Bootstrap;

//テンプレート指定 twigを呼び出す
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
	'cache' => Bootstrap::CACHE_DIR
]);

//初期データを設定
$dataArr = [
	'family_name' => '',
	'first_name' => '',
	'family_name_kana' => '',
	'first_name_kana' => '',
	'sex' => '',
	'year' => '',
	'month' => '',
	'day' => '',
	'zip1' => '',
	'zip2' => '',
	'address' => '',
	'email' => '',
	'tel1' => '',
	'tel2' => '',
	'tel3' => '',
	'traffic' => '',
	'contents' => ''
];

//エラーメッセージの定義、初期
$errArr = [];
foreach ($dataArr as $key => $value) {
	$errArr[$key] = '';//同じkey名で空の連想配列をつくっている($dataArrと同じ)
}
//valueだけ取り出すのは可、keyだけは不可(foreach $arr as value)
//array($yearArr, $monthArr, $dayArr)
//静的クラス

list ($yearArr, $monthArr, $dayArr) = initMaster::getDate();
//list:右辺の配列の要素を、左辺の変数に代入することができる
$sexArr = initMaster::getSex();
$trafficArr = initMaster::getTrafficWay();

$context = [];

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['sexArr'] = $sexArr;
$context['trafficArr'] = $trafficArr;
//         ↑key
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);

