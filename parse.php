<?
require_once "application/application.php";

$App = new Appliction();
$App->run();
$App->connectDB();
$App->loadLibrary("Utils");

require_once "application/models/goods.php";

$goods = new Goods();
if($_GET['id']){
	$goods->loadGoods(array('notparse'=>0, 'id'=>$_GET['id']));
}else{
	$goods->loadGoods(array('notparse'=>0));
}

header('Content-Type: text/html; charset=utf-8');
$goods->parsePrices();