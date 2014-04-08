<?
namespace HCWeb;

class Header {
	private static $prefiles = array();
	private static $files = array();
	public static $title=false;
	private static $currentpage = array();
	private static $printed = false;
	private static $description = false;
	
	public static function setDescrip($descrip){
		self::$description = $descrip;
	}
	
	public static function printDescrip(){
		if(self::$description) echo "<meta name=\"description\" content=\"".self::$description."\">";
	}
	
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
			$fsize = filesize($abs_path);
			if($matches[1] == 'css'){
				if($fsize > 512){
					echo "<link rel=\"stylesheet\" href=\"{$file}?t=".filemtime($abs_path)."\" />";
				} else {
					echo "<style type=\"text/css\">\n/** {$file} **/\n".file_get_contents($abs_path)."\n</style>";
				}
			} elseif($matches[1] == 'js') {
				if($fsize > 512) {
					echo "<script src=\"{$file}?t=".filemtime($abs_path)."\"></script>";
				} else {
					echo "<script type=\"text/javascript\">\n<!--\n// {$file}\n".file_get_contents($abs_path)."\n-->\n</script>";
				}
			} else {
				throw new \Exception("File {$file} not recognized as a CSS or JS file!!!");
			}
		}
		self::$prefiles = array();
		self::$files = array();
	}
}