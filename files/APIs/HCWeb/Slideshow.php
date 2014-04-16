<?
namespace HCWeb;

class Slideshow implements HTMLElement {
	private $containerID;
	private $photoUri;
	private static $first = true;
	private $overImages;
	private $images;
	private $delay = 4000;
	private $fadeDuration = 200;

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
	public function setDelay($delay){
		$this -> delay = $delay;
	}
	public function setFade($fade){
		$this -> fadeDuration = $fade;
	}
	
	public function addHTMLTop($html){
		$this -> overImages = $html;
	}
	
	public function getHTML(){
		$html = '';
		if(self::$first){
			$html = "<script type=\"text/javascript\" src=\"/js/Slideshow.js\"></script>";
			self::$first = false;
		}
		$html .= "<div id=\"{$this -> containerID}\"></div>";
		if($this -> overImages) $html .= $this -> overImages;
		$html .= "<script type=\"text/javascript\">
<!--
$(document).ready(function(){
	slide = new Slideshow('{$this -> containerID}','{$this -> photoUri}');
	slide.imgPool = ".json_encode($this -> images).";
	slide.params.delay = {$this -> delay};
	slide.params.fade = {$this -> fadeDuration};
	slide.Init();
});
-->
</script>";
		return $html;
	}
}