<?
namespace HCWeb\Linkbar;

class Link extends LinkItem {
	private $href;
	private $links;

	public function __construct($title,$href=false,$links=false,$className=false){
		parent::__construct($title,$className);
		$this -> href = $href;
		$this -> links = $links;
	}

	public function getHTML($currentpage="",$nav="nav",$navc="navc"){
		$style = $nav;
		if($currentpage == $this -> title){
			$style = $navc;
		}
		if($this -> className) $style .= " ".($this -> className);
		
		$href = $this -> href ? "href=\"{$this -> href}\"" : "href=\"javascript:;\"";
		$html = "<li class=\"{$style}\"><a {$href}>{$this -> title}</a>";
		if(is_array($this -> links)) {
			$html .= "<div class=\"ddmenu\">";
			foreach($this -> links as $data){
				$lname = $data[0];
				$href = $data[1];
				$html .= "<a href=\"$href\">$lname</a>";
			}
			$html .= "</div>";
		}
		$html .= "</li>\n";
		return $html;
	}
}