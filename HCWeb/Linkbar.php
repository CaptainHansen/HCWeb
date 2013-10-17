<?
namespace HCWeb;

class Linkbar {
	public $links;
	public $nav;
	public $navc;
	public $barClass;
	
	private $ddlinkcnt = 0;
	
	public function __construct($links,$nav='nav',$navc='navc',$barClass='linkbar'){
		$this -> links = $links;
		$this -> nav = $nav;
		$this -> navc = $navc;
		$this -> barClass = $barClass;
	}
	
	public function setNormalClass($className){ $this -> nav = $className; }
	public function setActiveClass($className) { $this -> navc = $className; }
	public function setBarClass($className) { $this -> barClass = $className; }
	
	private function mk_link($href,$name,$style){
		return  "<li class=\"$style\"><a href=\"$href\">$name</a></li>\n";
	}

	private function mk_dropdown($links,$name,$style,$main_href = false){
		$href = $main_href ? "href=\"{$main_href}\"" : "href=\"javascript:;\"";
		$html = "<li class=\"$style\"><a {$href}>$name</a>";
		$html .= "<div class=\"ddmenu\">";
		foreach($links as $id => $data){
			$lname = $data[0];
			$href = $data[1];
			$html .= "<a href=\"$href\">$lname</a>";
		}
		$html .= "</div></li>\n";
		return $html;
	}
	
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
		
		foreach($this -> links as $data){
			$name = $data[0];
			$item = $data[1];
			$style = $this -> nav;
			if($currentpage == $name){
				$style = $this -> navc;
			}
			if(is_array($item)){
				if(isset($data[2])){
					$html .= $this -> mk_dropdown($item,$name,$style,$data[2]);
				} else {
					$html .= $this -> mk_dropdown($item,$name,$style);
				}
			} else {
				$html .= $this -> mk_link($item,$name,$style);
			}
		}

		$html .= "</ul>\n";
		return $html;
	}
}