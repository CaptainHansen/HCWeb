<?
namespace HCWeb;

class Header {
	private static $cssPre = array();
	private static $jsPre = array();
	private static $css = array();
	private static $js = array();
	private static $javascript = "";
	
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

	public static function getExt($file) {
	  if(preg_match("/[^\.]+$/",$file,$matches)) {
	    return $matches[0];
	  } else {
	    return "";
	  }
	} 

	public static function prependCssJs() {
	  $files = func_get_args();
	  foreach($files as $file) {
	    $ext = self::getExt($file);
	    switch($ext) {
	      case "js":
	        self::$jsPre[] = $file;
	        break;
	      case "css":
	        self::$cssPre[] = $file;
	        if(self::$printed) self::printCss();
	        break;
      }
	  }
	}
	
	public static function addCssJs() {
	  $files = func_get_args();
	  foreach($files as $file) {
	    $ext = self::getExt($file);
	    switch($ext) {
	      case "js":
	        self::$js[] = $file;
	        break;
	      case "css":
	        self::$css[] = $file;
	        if(self::$printed) self::printCss();
	        break;
      }
	  }
	}
	
	public static function addJscript($js) {
	  self::$javascript .= $js."\n\n";
	}
	
	public static function checkFile($file) {
    if($file == '') return false;
    if(preg_match('!^/!',$file)){
      $abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
    } else {
      $abs_path = getcwd().'/'.$file;
    }
    if(!file_exists($abs_path)) {
      $file = "/defaults/".basename($file);
      $abs_path = $_SERVER['DOCUMENT_ROOT'].$file;
      if(!file_exists($abs_path)) return false;
    }
    return $abs_path;
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
	
	public static function printCss(){
		self::$printed = true;
		$allfiles = array_merge(self::$cssPre,self::$css);
		foreach($allfiles as $file){
		  $abs_path = self::checkFile($file);
		  if(!$abs_path) continue;
      if(filesize($abs_path) > 512){
        echo "<link rel=\"stylesheet\" href=\"{$file}?t=".filemtime($abs_path)."\" />";
      } else {
        echo "<style type=\"text/css\">\n/** {$file} **/\n".file_get_contents($abs_path)."\n</style>";
      }
		}
		self::$cssPre = array();
		self::$css = array();
	}
	
	public static function printJs() {
		$allfiles = array_merge(self::$jsPre,self::$js);
		foreach($allfiles as $file){
		  $abs_path = self::checkFile($file);
		  if(!$abs_path) continue;
      if(filesize($abs_path) > 512) {
        echo "<script src=\"{$file}?t=".filemtime($abs_path)."\"></script>";
      } else {
        echo "<script type=\"text/javascript\">\n<!--\n// {$file}\n".file_get_contents($abs_path)."\n-->\n</script>";
      }
    }
    if(self::$javascript != "") echo "<script type=\"text/javascript\">\n<!--\n".self::$javascript."-->\n</script>";
  }
}