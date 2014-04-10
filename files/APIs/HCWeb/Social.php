<?
namespace HCWeb;

//Social media links!!!
class Social {
	private $links = array();
	private $class = "social main";
	
	public function add($site,$url="",$visible=true,$id){
		$this -> links[$site] = array($url,$visible,$id);
	}
	
	public function setClass($cls){
		$this -> class = $cls;
	}
	
	public function fetchAll($include_invis=false){
		if($include_invis){
			$r = DB::query("select * from social_media order by seq asc");
		} else {
			$r = DB::query("select * from social_media where visible = 1 order by seq asc");
		}
		while($d = $r -> fetch_assoc()) {
			$this -> add($d['site'],$d['url'],$d['visible']==1,$d['ID']);
		}
	}
	
	public function __toString(){
		$html = "<div class=\"{$this -> class}\">";
		foreach($this -> links as $site => $ld){
			$html .= "<a class=\"{$site}\"";
			
			if($ld[0] != "") $html .= " href=\"{$ld[0]}\" target=\"_blank\"";
			if(isset($ld[2])) $html .= " id=\"site-{$ld[2]}\"";
			$html .= "><div></div></a>";
		}
		$html .= "</div>";
		return $html;
	}
}
			