<?
class Good{

    public $goods;
    public function __construct(){

    }

    public function loadGoods($data=null){
        if($data) {
            $this->goods = App::DB()->getAll("SELECT * FROM goods G LEFT JOIN shops S ON G.shop=S.shop_id WHERE id = ?i", $data);
        }else{
            $this->goods = App::DB()->getAll("SELECT * FROM goods G LEFT JOIN shops S ON G.shop=S.shop_id");
        }
    }


    public function parsePrices(){

        require_once App::PATH()."/parser/parser.php";
        $parser = new Parser;
        foreach($this->goods as $k => $good){
            $price = $parser->getPrice($good);
            if($price) {
                $this->savePrice($good['id'], $price);
                if(is_array($price))
                    echo $good['title'].">><del>".$price['old']."</del>>>".$price['new']."<br />";
                else
                    echo $good['title'].">>".$price."<br />";
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

    public function setId($id){
        $this->id = $id;
    }

    public function getshopData($id){
        return App::DB()->getRow("SELECT * FROM shops WHERE shop_id = ?i", $id);
    }

    public function saveGood($data){

        if(!$data['title']){
            require_once App::PATH()."/parser/parser.php";

            $data = array_merge($data, $this->getshopData($data['shop_id']));

            $parser = new Parser;
            $data['title'] = html_entity_decode($parser->getTitle($data['url'], $data));
        }
        if($this->id){
            App::DB()->query("UPDATE goods SET title = ?s, shop = ?i, url=?s WHERE id= ?i", $data['title'], $data['shop_id'], $data['url'], $this->id );
        }else{
            App::DB()->query("INSERT INTO goods SET title = ?s, shop = ?i, url=?s", $data['title'], $data['shop_id'], $data['url'] );
        }
    }

}