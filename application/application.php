<?
class App{
    public static $_Url;
    public static $_PATH;
    public static $mode;
	public static $user;
    private static $_DB;
    private static $Errors;


    static function __callStatic($method, $params){
        $per="_".$method;
        return self::$$per;
    }
    public static function DB(){
        return self::$_DB;
    }
    public static function PATH(){
        return self::$_PATH;
    }

    public function setDB($DB){
        self::$_DB = $DB;
    }
    public function setPATH($PATH){
        self::$_PATH= $PATH;
    }
    public function setMode($mode){
        self::$mode= $mode;
    }

    public function Error($err){
        self::$Errors[]=$err;
    }
    public function outErrors(){
		if(self::$mode=="browser"){
			$n="<br/>";
		}else{
			$n="\r\n";
		}

        if(self::$Errors){
            $content = "_______________<br />";
            $content.="Ошибки:".$n;
            foreach (self::$Errors as $error) {
                $content.= $error.$n;
            }
            if(self::$mode=="browser"){
                echo $content;
            }else{
                $file=fopen(date("Y-m-d")."error-log.txt","a+");
                fwrite($file, $content);
                fclose($file);
            }
        }

    }


    public function destruct(){
        self::outErrors();
    }

}
class Appliction{

    public $DB;
    /*public function connectDB(){

        mysql_connect("localhost", $conf["user"],  $conf["pass"])
        or die("Could not connect: ".mysql_error());
        //mysql_query("CREATE DATABASE ".$conf["db"]);
        mysql_select_db($conf["db"]) or die("Could not connect: ".mysql_error());
        mysql_set_charset('utf8');
        //mysql_query("SET NAMES 'cp1251'");
        //mysql_query("SET CHARACTER SET 'cp1251'");
    }*/
    public function connectDB(){
        require_once "safemysql/safemysql.php";
        $conf = require "sqlconnect.php";
        $this->DB = new SafeMySQL($conf);
        App::setDB($this->DB);
    }
	public function reconnectDB(){
		require_once "safemysql/safemysql.php";
		$conf = require "sqlconnect.php";
		$this->DB = new SafeMySQL($conf);
		App::setDB($this->DB);
	}

    public function run(){
        $aa = __DIR__;
        App::setPATH( $aa );

        App::$_Url=explode("/",$_SERVER['REDIRECT_URL']);
        array_shift(App::$_Url);

        if($_SERVER['REMOTE_ADDR']){
            App::setMode("browser");
        }else{
            App::setMode("console");
        }

		if($_COOKIE['admin']=="magics"){
			App::$user = "admin";
		}
    }

    public function view($template, $view, $params){
        foreach ($params as $k => $p) {
            $$k=$p;
        }

        ob_start();
        require_once App::$_PATH."/view/".$view.".php";
        $page['content'] = ob_get_contents();
        ob_end_clean();
        require_once App::$_PATH."/view/".$template.".php";
    }


    public function initializing(){
        //$this->DB->query("CREATE TABLE IF NOT EXISTS `goods`(`id` INT AUTO_INCREMENT PRIMARY KEY, `title` TEXT, `shop` VARCHAR(10), `url` TEXT, `title_exp` TEXT, `price_exp` TEXT)") or die("Error: ".mysql_error());
        //$this->DB->query("CREATE TABLE IF NOT EXISTS `shops`(`id` INT AUTO_INCREMENT PRIMARY KEY, `title` TEXT)") or die("Error: ".mysql_error());
        //$this->DB->query("CREATE TABLE IF NOT EXISTS `prices`(`id` INT AUTO_INCREMENT PRIMARY KEY, `good_id` INT, `date` DATE, `price` VARCHAR(10))") or die("Error: ".mysql_error());
    }

	public function loadLibrary($str){
		include App::$_PATH."/libraries/".$str.".php";
	}



    public function __destruct(){
        App::destruct();
    }

}
