<?
namespace HCWeb\Linkbar;

abstract class LinkItem implements \HCWeb\HTMLElement {
	protected $title;
	protected $className;
	
	public function __construct($title,$className){
		$this -> title = $title;
		$this -> className = $className;
	}
}