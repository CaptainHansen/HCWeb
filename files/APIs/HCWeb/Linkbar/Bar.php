<?
namespace HCWeb\Linkbar;
use \HCWeb\HTMLElement;

class Bar implements HTMLElement {
	private $links = array();
	public $nav;
	public $navc;
	public $barClass;
	private $barID = false;
	public $script = true;
	
	public function __construct($nav='nav',$navc='navc',$barClass='linkbar'){
		$this -> nav = $nav;
		$this -> navc = $navc;
		$this -> barClass = $barClass;
	}
	
	public function add(LinkItem $lnk) { $this -> links[] = $lnk; }
	
	public function setBarID($id){
		$this -> barID = $id;
	}
	
	public function setNormalClass($className){ $this -> nav = $className; }
	public function setActiveClass($className) { $this -> navc = $className; }
	public function setBarClass($className) { $this -> barClass = $className; }
	
	public function __toString(){
		$html = "";
		if($this -> script){
		$html .= "<script type=\"text/javascript\"><!--\n";
		$html .= "$(document).ready(function(){
	$('.{$this -> barClass} li').mouseenter(function(){
		$(this).find('.ddmenu').slideDown(100);
	}).mouseleave(function(){
		$(this).find('.ddmenu').slideUp(100);
	});
});";
		$html .= "\n--></script>";
		}
	
		$id = "";
		if($this -> barID) $id = "id=\"{$this -> barID}\" ";
		
		$html .= "<ul {$id}class=\"{$this -> barClass}\">";

		$active = \HCWeb\Header::getCurPage($this -> barID);
		
		foreach($this -> links as $lnk){
			$class = $this -> nav;
			if($active) {
				if($active == $lnk -> getTitle()){
					$class = $this -> navc;
				}
			}
			$html .= $lnk -> getHTML($class);
		}

		$html .= "</ul>\n";
		return $html;
	}
	
	public function getHTML($script=true){
		trigger_error("getHTML() - Method deprecated and will be removed in a future version.  Use __toString() instead",E_USER_NOTICE);
		return $this -> __toString();
	}
}