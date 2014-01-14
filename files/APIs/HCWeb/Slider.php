<?
namespace HCWeb;

class Slider implements HTMLElement {
	private $id;
	private $value;
	private $cname = false;
	private static $first = true;

	public function __construct($id,$value){
		$this -> id = $id;
		$this -> value = intval($value);
	}
	
	public function setClass($cname){
		$this -> cname = $cname;
	}
	
	public function getHTML(){
		$html = '';
		if(self::$first){
			$html .= '<script type="text/javascript" src="/js/Slider.js"></script><style type="text/css">
.HCSlider div.track {
	background-color: #aaa;
	height: 10px;
	width: 500px;
	border-radius: 5px;
	position: relative;
	margin: 5px;
}
.HCSlider div.slider {
	background-color: rgba(0,0,0,0.5);
	width: 20px;
	height: 20px;
	border-radius: 10px;
	position: absolute;
	top: -5px;
	left: -10px;
}
</style>';
		$html .= '<div class="HCSlider'.($cname ? $cname : "").'"><div class="track"><div class="slider" style="margin-left: '.$this -> value.'%;"><input type="hidden" id="'.$this -> id.'" value="'+v+'"></div></div></div>';
	}
}