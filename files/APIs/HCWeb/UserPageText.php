<?
namespace HCWeb;

class UserPageText {
	public $page_text;
	
	public function __construct($filesroot,$page){
		if(file_exists($filesroot."/".$page)){
			$this -> page_text = file_get_contents($filesroot."/".$page);
		}
	}
	
	public function getPlainText(){
		return $this->page_text;
	}
	
	public function getHTML(){
		return nl2br(htmlspecialchars($this->page_text,ENT_QUOTES));
	}
	
	public function getHTMLSplitDivs($div_class){
		$page_ar = explode("\n\n",$this->page_text);
		$ret = "";
		foreach($page_ar as $text){
			$ret.= "<div class=\"{$div_class}\">";
			$ret.= nl2br(htmlspecialchars($text,ENT_QUOTES));
			$ret.= "</div>";
		}
		return $ret;
	}
}