<?
namespace Photos;

class Hash {
	public $sqsize;
	private $photo;
	public $pix_grey;
	public $avg_color;
	
	public function __construct($file){
		$this -> photo = new \Imagick($file);
		$this -> sqsize = 8;
		$this -> pix_grey = array();
	}
	
	public function __destruct(){
		$this -> photo -> destroy();
	}
	
	private function average_pixel($pixel){
		$ar = $pixel -> getColor();
		unset($ar['a']);
		return array_sum($ar)/3;
	}
	
	public function getHash(){
		$photo = $this -> photo;
		$sqsize = $this -> sqsize;
		$photo -> adaptiveResizeImage($this -> sqsize,$this -> sqsize);
		
		$pix_iter = $photo -> getPixelIterator();
		foreach($pix_iter as $orw => $pixels){
			foreach($pixels as $col => $pixel){
				array_push($this -> pix_grey, $this -> average_pixel($pixel));
			}
		}
		$this -> avg_color = array_sum($this -> pix_grey)/($sqsize*$sqsize);
		
		$i = 0;
		$ints = "";
		foreach($this -> pix_grey as $id => $this_col){
			$i++;
			if($this_col < $this -> avg_color){
				$ints .= "0";
			} else {
				$ints .= "1";
			}
		}
		$photo -> destroy();
		$bc = base_convert($ints,2,16);
		while(strlen($bc) < 16) $bc = "0$bc";
		return $bc;
	}
}