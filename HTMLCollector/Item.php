<?
namespace HTMLCollector;

abstract class Item {
	protected $html;
	protected $funcs=array();
	protected $script;
	
	public function getHTML() {
		return $this -> html;
	}
	
	public function getFuncs() {
		return $this -> funcs;
	}
	
	public function getScript() {
		return $this -> script;
	}
}