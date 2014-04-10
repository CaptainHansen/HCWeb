<?
namespace HCWeb;

//Social media links!!!
class Social {
	private $links = array();
	
	public function add($id,$url){
		$this -> links[$id] = $url;
	}
	
	public function __toString(){
		$html = "<div class=\"social\">";
		foreach($this -> links as $id => $url){
			$html .= "<a class=\"{$id}\"";
			if($url != "") $html .= " href=\"{$url}\" target=\"_blank\"";
			$html .= "><div></div></a>";
		}
		$html .= "</div>";
		return $html;
	}
}
			