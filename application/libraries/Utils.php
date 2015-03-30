<?php
/**
 * Created by PhpStorm.
 * User: Денис
 * Date: 07.03.2015
 * Time: 19:49
 */

class Utils {

	public static function htmlspecialchars($str){
		return htmlspecialchars($str, ENT_COMPAT|ENT_QUOTES, 'utf-8');
	}

	public static function link($href, $anchor, $class=null){
		$class_=$class?'class="'.$class.'"':"";
		$href_ = "data:text/html,".Utils::htmlspecialchars('<html><meta http-equiv="refresh" content="0; url=\''.$href.'\'"></html>');
		return '<a '.$class_.' href="'.$href_.'">'.$anchor.'</a>';
	}

} 