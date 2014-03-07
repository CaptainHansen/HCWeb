<?
namespace HCWeb;

class Header {
	private static $prefiles = array();
	private static $files = array();
	public static $title=false;
	private static $currentpage = array();
	private static $printed = false;
	
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
	
	public static function prependCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			self::$prefiles[] = $file;
		}
		if(self::$printed) self::printCssJs();
	}
	
	public static function addCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			self::$files[] = $file;
		}
		if(self::$printed) self::printCssJs();
	}
	
	public static function printCssJs(){
		self::$printed = true;
		$allfiles = array_merge(self::$prefiles,self::$files);
		foreach($allfiles as $file){
			if($file == '') continue;
			if(preg_match('!^/!',$file)){
				$abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
			} else {
				$abs_path = getcwd().'/'.$file;
			}
			if(!file_exists($abs_path)) {
				$file = "/defaults/".basename($file);
				$abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
				if(!file_exists($abs_path)) continue;
			}
			preg_match('/\.([^\.]+)$/',$file,$matches);
			if($matches[1] == 'css'){
				echo "<link rel=\"stylesheet\" href=\"{$file}?t=".filemtime($abs_path)."\" />";
			} elseif($matches[1] == 'js') {
				echo "<script src=\"{$file}?t=".filemtime($abs_path)."\"></script>";
			} else {
				throw new \Exception("File {$file} not recognized as a CSS or JS file!!!");
			}
		}
		self::$prefiles = array();
		self::$files = array();
	}
}