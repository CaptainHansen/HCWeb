<?
namespace HCWeb\Linkbar;

class Search extends LinkItem {
	private $id;
	private $onsearch;
	
	public function __construct($className,$id,$onsearch){
		parent::__construct("",$className);
		$this -> id = $id;
		$this -> onsearch = $onsearch;
	}
	
	public function getHTML(){
		return "<div class=\"{$this -> className}\"><input type=\"search\" id=\"{$this -> id}\" onsearch=\"{$this -> onsearch}\"></div>";
	}
}