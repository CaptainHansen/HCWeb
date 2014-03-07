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
	
	public function add(LinkItem $lnk) { $this -> links[] = $lnk; }
	
	public function setNormalClass($className){ $this -> nav = $className; }
	public function setActiveClass($className) { $this -> navc = $className; }
	public function setBarClass($className) { $this -> barClass = $className; }
	
	public function getHTML($script=true){
		if($script) {
		$html = "<script type=\"text/javascript\"><!--\n";
		$html .= "$(document).ready(function(){
	$('.{$this -> barClass} li').mouseenter(function(){
		$(this).find('.ddmenu').slideDown(100);
	}).mouseleave(function(){
		$(this).find('.ddmenu').slideUp(100);
	});
});";
		$html .= "\n--></script>";
		}
		$html .= "<ul class=\"{$this -> barClass}\">";
		
		$active = \HCWeb\Header::getCurPage($this -> barClass);
		
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
}