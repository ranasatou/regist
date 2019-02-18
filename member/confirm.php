<?php
/*
ファイルパス：C:\xampp\htdocs\member\confirm.php
ファイル名：confirm.php
アクセスURL:http://localhost/member/confirm.php
*/
namespace member;

require_once dirname(__FILE__) . '\Bootstrap.class.php';

use member\master\initMaster;
use member\lib\Database;
use member\lib\Common;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);

$twig = new \Twig_Environment($loader, [
	'cache' => Bootstrap::CACHE_DIR
]);

$db = new Database(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME);
$common = new Common();

//モード判定（どの画面から来たかの判断）
//登録画面から来た場合
if (isset ($_POST['confirm']) === true) $mode = 'confirm';
//戻る場合
if (isset($_POST['back']) === true) $mode = 'back';
//登録完了
if (isset($_POST['complete']) === true) $mode = 'complete';
//ボタンのモードによって処理をかえる
switch ($mode) {
	case 'confirm': //新規登録
			//データを受け継ぐ	
			//↓この情報は入力には必要ない
		unset($_POST['confirm']);//unset:配列から値を消す

		$dataArr = $_POST;

		//この値を入れないでPOSTするとUndefinedとなるので未定義の場合は空白状態としてセットしておく
		if (isset($_POST['sex']) === false) $dataArr['sex'] = "";//なかったら作って空の配列を入れておく
		if (isset($_POST['traffic']) === false) $dataArr['traffic'] = [];

		//エラーメッセージの配列作成
		$errArr = $common->errorCheck($dataArr);//入力された値を引数で入れる
		$err_check = $common->getErrorFlg();
		//err_check = false →エラーがありますよ！
		//err_check = true →エラーがないですよ！
		//エラー無ければconfirm.tpl　あるとregist.tpl
		$template = ($err_check === true) ? 'confirm.html.twig' : 'regist.html.twig';
		
		break;//switch文を出る

	case 'back': //戻ってきたとき
		     //ポストされたデータを元に戻すので、$dataArrにいれる
		$dataArr = $_POST;

		unset($dataArr['back']);

		//エラーも定義しておかないと、Undefinedエラーが出る
		foreach ($dataArr as $key => $value) {
			$errArr[$key] = '';
		}

		$template = 'regist.html.twig';
		break;

	case 'complete': //登録完了
		$dataArr = $_POST;

		//↓この情報はいらないので外しておく
		unset($dataArr['complete']);
		$column = '';
		$insData = '';

		//foreachの中でSQL文を作る
		foreach ($dataArr as $key => $value) {
			$column .= $key . ', ';
			if ($key === 'traffic') $value = implode('_', $value);
			$insData .= ($key === 'sex') ? $db->quote($value) . ', ' : $db->str_quote($value) . ', ';
		}
	
		$query = " INSERT INTO member ( "
			. $column
			. " regist_date "
			. " ) VALUES ( "
			. $insData
			. "     NOW() "//現在時刻が取れる(SQL)
			. " ) ";
		$res = $db->execute($query);
		$db->close();

		if ($res === true) {
			//登録成功時は完成ページへ
			header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php');//header関数:リダイレクト先を指定　URLに自動的にアクセスする
			exit();//phpの処理を終わらせる　header関数を使った後は必ずexit
		} else {
			//登録失敗時は登録画面に戻る
			$template = 'regist.html.twig';

			foreach ($dataArr as $key => $value) {
				$errArr[$key] = '';
			}
		}

		break;
	}
	$sexArr = initMaster::getSex();

$trafficArr = initMaster::getTrafficWay();

$context['sexArr'] = $sexArr;
$context['trafficArr'] = $trafficArr;

list ($yearArr, $monthArr, $dayArr) = initMaster::getDate();

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$template = $twig->loadTemplate($template);
$template->display($context);
