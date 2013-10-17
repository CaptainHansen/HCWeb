<?
namespace HCWeb\CSS3_Matrix;

class Core {
	public $rotation;
	public $x_trans;
	public $y_trans;
	public $scale;
	private $matrix;
	
	public function __construct($rotation=0,$x_trans=0,$y_trans=0,$scale=1){
		$this -> rotation = $rotation;	//DEGREES!!!!
		$this -> x_trans = $x_trans;
		$this -> y_trans = $y_trans;
		$this -> scale = $scale;
		$this -> resetMatrix();
		$this -> combineTransforms();
	}
	
	public function resetMatrix(){
		$this -> matrix = array(
		array(1,0,0),
		array(0,1,0),
		array(0,0,1));
	}
	
	public function combineTransforms(){
		$a = ($this -> rotation)*pi()/180;
		$s = $this -> scale;
		$new = array(
			array($s*cos($a),-$s*sin($a),$this -> x_trans),
			array($s*sin($a),$s*cos($a),$this -> y_trans),
			array(0,0,1),
		);
		
		////adding scale information to the rotation and transformation information
//		$new2 = mat_mul($new,array(array($this -> scale,0,0),array(0,$this -> scale,0),array(0,0,1)));
		
		$this -> matrix = $this -> mat_mul($new,$this->matrix);
	}
	
	public static function mat_mul($mat1,$mat2){
		$final = array();
		foreach($mat1 as $rowid => $row){
			$res[$rowid] = array();
			for($i = 0; $i < count($mat1); $i++){
				$num = 0;
				foreach($row as $cid => $n){
					$num += $n * $mat2[$cid][$i];
				}
				$final[$rowid][$i]=$num;	//filling columns by rows.
			}
		}
		return $final;
	}
	
	public function get_css(){
		$m = $this -> matrix;
		$matrix_text = "matrix({$m[0][0]},{$m[0][1]},{$m[1][0]},{$m[1][1]},{$m[0][2]},{$m[1][2]})";
		$transforms = array('transform','-ms-transform','-webkit-transform','-moz-transform','-o-transform');
		$ret = "";
		foreach($transforms as $id => $name){
			$ret .= "$name: $matrix_text;";
		}
		return $ret;
	}
}