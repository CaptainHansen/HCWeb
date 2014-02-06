<?
namespace HCWeb;

class Header {
	private static $prefiles = array();
	private static $files = array();
	public static $title=false;
	public static $currentpage;
	
	public static function prependCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			self::$prefiles[] = $file;
		}
	}
	
	public static function addCssJs(){
		$files = func_get_args();
		foreach($files as $file){
			self::$files[] = $file;
		}
	}
	
	public static function printCssJs(){
		$allfiles = array_merge(self::$prefiles,self::$files);
		foreach($allfiles as $file){
			if($file == '') continue;
			if(preg_match('!^/!',$file)){
				$abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
			} else {
				$abs_path = getcwd().'/'.$file;
			}
			if(!file_exists($abs_path)) continue;
			preg_match('/\.([^\.]+)$/',$file,$matches);
			if($matches[1] == 'css'){
				echo "<link rel=\"stylesheet\" href=\"{$file}?t=".filemtime($abs_path)."\" />";
			} elseif($matches[1] == 'js') {
				echo "<script src=\"{$file}?t=".filemtime($abs_path)."\"></script>";
			} else {
				throw new \Exception("File {$file} not recognized as a CSS or JS file!!!");
			}
		}
	}
}