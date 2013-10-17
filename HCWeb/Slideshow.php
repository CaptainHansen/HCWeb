<?
namespace HCWeb;

class Slideshow implements HTMLElement {
	private $containerID;
	private $photoUri;
	private static $first = true;
	private $overImages;
	private $images;

	public function __construct($containerID,$photoUri){
		$this -> containerID = $containerID;
		$this -> photoUri = $photoUri;
		$this -> overImages = false;
		$this -> images = array();
	}
	
	public function setImages($images){
		$this -> images = array_merge($images,$this -> images);
	}
	public function addImage($image){
		$this -> images[] = $image;
	}
	
	public function addHTMLTop($html){
		$this -> overImages = $html;
	}
	
	public function getHTML(){
		$html = '';
		if(self::$first){
			$html = "<script type=\"text/javascript\" src=\"/js/Slideshow.js\"></script><style type=\"text/css\">
.HCSlideshowB, .HCSlideshowT {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	display: none;
	background-position: center;
	background-repeat: no-repeat;
	background-color: black;
}
</style>";
			self::$first = false;
		}
		$html .= "<div id=\"{$this -> containerID}\"><input type=\"hidden\" id=\"pool\" value='".json_encode($this -> images)."'>";
		if($this -> overImages) $html .= $this -> overImages;
		$html .= "<div class=\"HCSlideshowB\"></div><div class=\"HCSlideshowT\"></div></div>";
		$html .= "<script type=\"text/javascript\">
<!--
$(document).ready(function(){
	slide = new Slideshow('{$this -> containerID}','{$this -> photoUri}');
	slide.Init();
});
-->
</script>";
		return $html;
	}
}