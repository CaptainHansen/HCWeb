<?
namespace HCWeb;

class CssJs {
	private $file;
	private $attr;
	
	public function __construct($file,$attr=''){
		$this -> file = $file;
		$this -> attr = $attr;
	}
	
	public function Get(){
		$file = $this -> file;
		if($file == '') return false;
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
				return "<link rel=\"stylesheet\" href=\"{$file}?t=".filemtime($abs_path)."\" />";
			} else {
				return "<style type=\"text/css\">\n/** {$file} **/\n".file_get_contents($abs_path)."\n</style>";
			}
		} elseif($matches[1] == 'js') {
			if($fsize > 512) {
				return "<script src=\"{$file}?t=".filemtime($abs_path)."\" {$this -> attr}></script>";
			} else {
				return "<script type=\"text/javascript\" {$this -> attr}>\n<!--\n// {$file}\n".file_get_contents($abs_path)."\n-->\n</script>";
			}
		} else {
			throw new \Exception("File {$file} not recognized as a CSS or JS file!!!");
		}
	}
}