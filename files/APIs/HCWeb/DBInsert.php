<?
namespace HCWeb;

class DBInsert extends DBAction {
	private $data;

	public function __construct($table,$data=array(),$val_column,$seq=false,$pid=false){
		parent::__construct($table,$val_column,$seq,$pid);
		$this -> data = $data;
	}
	
	public function setVal($v){
		$this -> data[$this -> val_column] = $v;
	}
	
	public function runQuery(){
		if(!isset($this -> data[$this -> val_column])) return false;
		return DB::insert($this -> table,$this -> data,$this -> seq, $this -> pid);
	}
}