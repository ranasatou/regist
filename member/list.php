<?php
/*
ファイルパス：C:\xampp\htdocs\member\list.php
ファイル名：list.php
アクセスURL:http://localhost/member/list.php
*/
namespace member;

require_once dirname(__FILE__) . '\Bootstrap.class.php';

use member\Bootstrap;
use member\master\initMaster;
use member\lib\Database;
use member\lib\Common;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [ 'cache' => Bootstrap::CACHE_DIR
]);
$db = new Database(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME);

$query = " SELECT "
	. "     mem_id, "
	. "     family_name, "
    . "     first_name, "
	. "     family_name_kana, "
	. "     first_name_kana, "
	. "     sex, "
	. "     email, "
	. "     traffic, "
	. "     regist_date "
	. " FROM "
	. "     member ";

$dataArr = $db->select($query);
$db->close();

$context = [];
$context['dataArr'] = $dataArr;
var_dump($dataArr);
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);