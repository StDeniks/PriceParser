<?
class Goods{

	public $goods;
	public function __construct(){

	}

	public function loadGoods($data=null){
		if($data) {
			foreach ($data as $f=>$v){
				$where[]= App::DB()->parse("?n = ?s", $f, $v);
			}
			$wherestr = implode(" AND ", $where);
			$this->goods = App::DB()->getInd("id", "SELECT * FROM goods G LEFT JOIN shops S ON G.shop=S.shop_id WHERE ?p", $wherestr);
		}else{
			$this->goods = App::DB()->getInd("id", "SELECT * FROM goods G LEFT JOIN shops S ON G.shop=S.shop_id");
		}
	}

	public function loadPrices($data=null){
		$wherestr="";
		if($data) {
			foreach ($data as $f => $v) {
				$where[] = App::DB()->parse($f, $v);
			}
			$wherestr = " AND ".implode(" AND ", $where);
		}

		foreach ($this->goods as $k=>$good) {
			$this->goods[$k]['prices'] = App::DB()->getInd("date", "SELECT * FROM prices WHERE good_id = ?i ?p", $good['id'], $wherestr);
			$keys = array_keys($this->goods[$k]['prices']);
			$this->goods[$k]['prices_from'] = date("d.m.Y", strtotime($keys[0]));
			$this->goods[$k]['prices_to'] =  date("d.m.Y", strtotime($keys[count($keys)-1]));
		}


	}


	public function parsePrices(){

		require_once App::PATH()."/parser/parser.php";
		$parser = new Parser;
		foreach($this->goods as $k => $good){
			$price = $parser->getPrice($good);
			if($price) {
				$this->savePrice($good['id'], $price);
				echo $good['id'].$good['title'].">>".$price['old'].">>".$price['new']."<br />";
			}else{

			}
		}

	}

	public function savePrice($id, $price){
		try {

			App::DB()->query("INSERT INTO prices SET good_id= ?i, price= ?s, old_price= ?s, date=NOW()", $id, $price['new'], $price['old']);

		}catch(Exception $e){
			App::Error($e->getMessage());
		}
	}

	public function getshopData($id){
		return App::DB()->getRow("SELECT * FROM shops WHERE shop_id = ?i", $id);
	}

	public function setId($id){
		$this->id=$id;
	}

	public function saveGood($data){
		if(!$data['title']){
			require_once App::PATH()."/parser/parser.php";

			$parser = new Parser;
			$shop = $this->getshopData($data['shop']);
			$data['title'] = html_entity_decode($parser->getTitle($data['url'], $shop));

		}

		if($this->id){
			App::DB()->query("UPDATE goods SET ?u WHERE id= ?i", $data, $this->id );
		}else{
			App::DB()->query("INSERT INTO goods SET ?u", $data);
			return App::DB()->insertId();
		}
	}

	public function testCheckBoxies($chs, $data){
		foreach($chs as $ch){
			if(empty($data[$ch])) $data[$ch]=0;
		}
		return $data;
	}

}