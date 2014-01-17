<?
namespace HCWeb;

class DBUpdate extends DBAction {
	private $val = false;
	private $id;
	
	public function __construct($table,$id,$val_column){
		parent::__construct($table,$val_column,false,false);
		$this -> id = $id;
	}
	
	public function setVal($v){
		$this -> val = $v;
	}
	
	public function runQuery(){
		if(!$this -> val) return false;
		return DB::update($this -> table,$this -> id, array($this -> val_column => $this -> val));
	}
}