<?
namespace HCWeb\CSS3_Matrix;

class Random extends Core {
	private $max_rot;
	private $max_x_transform;
	private $max_y_transform;
	private $max_scale;
	private $RandCount;

	public function __construct($max_rot,$max_x_transform,$max_y_transform,$max_scale=0){	//here, max_scale is the maximum VARIATION FROM 1!!!! (.1 would give a range of 0.9 to 1.1 for the scale of the object)
		parent::__construct();
		$this -> setRandThresholds($max_rot,$max_x_transform,$max_y_transform,$max_scale);
	}

	public function setRandThresholds($max_rot,$max_x_transform,$max_y_transform,$max_scale=0){
		$this -> max_rot = $max_rot;
		$this -> max_x_transform = $max_x_transform;
		$this -> max_y_transform = $max_y_transform;
		$this -> max_scale = $max_scale;
	}
		
	public function randTrans(){
		srand(microtime(true)+$this -> RandCount);
		$this -> rotation = $this -> rand_dec($this -> max_rot,2);
		$this -> x_trans = $this -> rand_dec($this -> max_x_transform,0);
		$this -> y_trans = $this -> rand_dec($this -> max_y_transform,0);
		if($this -> max_scale > 0){
			$this -> scale = ($this -> rand_dec($this -> max_scale,2))+1;
		}
		$this -> combineTransforms();
		$this -> RandCount ++;
	}
	
	public function resetRandReturn(){
		$this -> resetMatrix();
		$this -> randTrans();
		return $this -> get_css();
	}
		
	private function rand_dec($max_val,$dec_points,$can_be_neg = true){
		if($max_val == 0) return 0;
		$pten = 1;
		for($i = 1; $i <= $dec_points; $i++){
			$pten = $pten*10;
		}
		$rval = rand()%($max_val*$pten);
		if(rand()%2 == 1) $rval = $rval * -1;
		return $rval / $pten;
	}
}