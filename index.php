<?
require_once "application/application.php";

$App = new Appliction();
$App->run();
$App->connectDB();
$App->loadLibrary("Utils");

switch(App::$_Url[0]){
	case "editgood":
		$id=App::$_Url[1];
		require_once App::$_PATH."/models/goods.php";
		$good = new Goods();

		if($_POST){
			$good->setId($id);
			$data = $good->testCheckBoxies(array('notshow', 'notparse'), $_POST);
			$id = $good->saveGood($data);
			if(!$_REQUEST['id']) header("Location: ".$_SERVER['REDIRECT_URL'].$id);
		}

		if($id){
			$good->loadGoods(array("id" => $id));
		}

		$shops=App::DB()->getAll("SELECT * FROM shops");

		$App->view('main', 'editgood', array('good'=>$good->goods[$id],
											'shops'=>$shops,
											'page' => $page,
											));
		break;

	case "viewgood":
		$id=App::$_Url[1];
		require_once App::$_PATH."/models/goods.php";
		$good = new Goods();
		$good->loadGoods(array("id" => $id));
		$good->loadPrices();
		$page['title']= htmlspecialchars($good->goods[$id]['title']);
		$App->view('main', 'viewgood', array('good'=>$good->goods[$id],
			'page' => $page,
		));

		break;

	case "":
		require_once App::$_PATH."/models/goods.php";
		$good = new Goods();
		$good->loadGoods(array('notshow'=>0));
		$good->loadPrices();
		$page['title']="Просмотр ценовой динамики за 2015 год";
		$App->view('main', 'viewgoods', array('goods'=>$good->goods,
			'page' => $page,
		));

		break;


}