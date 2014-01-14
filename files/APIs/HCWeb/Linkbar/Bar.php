<?
namespace HCWeb\Linkbar;
use \HCWeb\HTMLElement;

class Bar implements HTMLElement {
	private $links = array();
	public $nav;
	public $navc;
	public $barClass;
	
	public function __construct($nav='nav',$navc='navc',$barClass='linkbar'){
		$this -> nav = $nav;
		$this -> navc = $navc;
		$this -> barClass = $barClass;
	}
	
	public function addLink(LinkItem $lnk) { $this -> links[] = $lnk; }
	
	public function setNormalClass($className){ $this -> nav = $className; }
	public function setActiveClass($className) { $this -> navc = $className; }
	public function setBarClass($className) { $this -> barClass = $className; }
	
	public function getHTML($currentpage="",$script=false){
		$html = "<script type=\"text/javascript\"><!--\n";
		if(!$script) {
		$html .= "$(document).ready(function(){
	$('li.{$this -> nav},li.{$this -> navc}').mouseenter(function(){
		$(this).find('.ddmenu').slideDown(100);
	}).mouseleave(function(){
		$(this).find('.ddmenu').slideUp(100);
	});
});";
		}
		$html .= "\n--></script>";
		$html .= "<ul class=\"{$this -> barClass}\">";
		
		foreach($this -> links as $lnk){
			$html .= $lnk -> getHTML($currentpage,$this -> nav, $this -> navc);
		}

		$html .= "</ul>\n";
		return $html;
	}
}