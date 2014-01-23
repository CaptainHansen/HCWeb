<?
namespace HCWeb;

class Header {
	private static $files=array('','/style.css','/js/jquery-1.8.3.js','/js/EasyJax.js');
	public static $title="New Website";
	public static $currentpage;
	
	public static function Init(){
		if(Auth::isLoggedIn()){
			self::$files[0] = "/admin.css";
		}
	}
	
	public function addCssJs($file){
		self::$files[] = $file;
	}
	
	public function printCssJs(){
		foreach(self::$files as $file){
			if($file == '') continue;
			if(preg_match('!^/!',$file)){
				$abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
			} else {
				$abs_path = getcwd().'/'.$file;
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
	}
}