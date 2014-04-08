<?
namespace HCWeb;

class Header {
	private static $prefiles = array();
	private static $files = array();
	public static $title=false;
	private static $currentpage = array();
	private static $printed = false;
	private static $description = false;
	private static $og = array();
	
	public static function setCurPage($curpageID,$item){
		if(!isset(self::$currentpage[$curpageID])){
			self::$currentpage[$curpageID] = $item;
			return true;
		}
		return false;
	}
	
	public static function getCurPage($curpageID){
		if(isset(self::$currentpage[$curpageID])){
			return self::$currentpage[$curpageID];
		}
		return false;
	}
	
	public static function setDescrip($descrip){
		self::$description = $descrip;
	}
	
	public static function printDescrip(){
		if(self::$description) echo "<meta name=\"description\" content=\"".self::$description."\">";
	}
	
	public static function addOg($tag,$val) {
		self::$og[$tag] = $val;
	}
	
	public static function printOg(){
		foreach(self::$og as $tag => $val){
			echo "<meta property=\"og:{$tag}\" content=\"{$val}\">";
		}
	}
	
	public static function prependCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			if($file instanceof CssJs){
				self::$prefiles[] = $file;
			} else {
				self::$prefiles[] = new CssJs($file);
			}
		}
		if(self::$printed) self::printCssJs();
	}
	
	public static function addCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			if($file instanceof CssJs) {
				self::$files[] = $file;
			} else {
				self::$files[] = new CssJs($file);
			}
		}
		if(self::$printed) self::printCssJs();
	}
	
	public static function printCssJs(){
		self::$printed = true;
		$allfiles = array_merge(self::$prefiles,self::$files);
		foreach($allfiles as $file) echo $file -> Get();
		self::$prefiles = array();
		self::$files = array();
	}
	
	public static function printAll() {
		echo "<title>".self::$title."</title>";
		self::printDescrip();
		self::printOg();
		self::printCssJs();
	}
}