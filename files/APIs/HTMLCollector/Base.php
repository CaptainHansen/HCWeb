<?
namespace HTMLCollector;

class Base {
	private $items = array();
	
	public function addItem(\HTMLCollector\Item $item){
		array_push($this -> items, $item);
	}
	
	public function getAllHTML(){
		$html = "";
		foreach($this -> items as $item){
			$html .= $item -> getHTML();
		}
		return $html;
	}
	
	public function getAllFuncs(){
		$funcs = array();
		foreach($this -> items as $item){
			$funcs = array_merge($funcs, $item -> getFuncs());
		}
		$funcs_string = implode("();\n",$funcs)."();\n";
		return $funcs_string;
	}
	
	public function getAllScripts(){
		$script = "";
		foreach($this -> items as $item){
			$script .= $item -> getScript()."\n";
		}
		return $script;
	}
	
	public function getJSCode(){
		$ret = "<script type=\"text/javascript\">\n<!--\n";
		$ret .= $this -> getAllFuncs();
		$ret .= "\n";
		$ret .= $this -> getAllScripts();
		$ret .= "\n-->\n</script>";
		return $ret;
	}
}