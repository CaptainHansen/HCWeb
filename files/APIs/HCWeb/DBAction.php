<?
namespace HCWeb;

abstract class DBAction {
	protected $table;
	protected $val_column;
	protected $seq=false;
	protected $pid=false;
	
	public function __construct($table,$val_column,$seq,$pid){
		$this -> table = $table;
		$this -> val_column = $val_column;
		$this -> seq = $seq;
		$this -> pid = $pid;
	}
	
	public abstract function setVal($v);

	public abstract function runQuery();
}