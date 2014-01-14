<?
namespace HCWeb\Linkbar;

class Text extends LinkItem {
	public function __construct($title,$className) {
		parent::__construct($title,$className);
	}
	
	public function getHTML(){
		return "<div class=\"{$this -> className}\">{$this -> title}</div>";
	}
}