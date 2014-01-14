<?
namespace Photos;
use \HCWeb\DB;

class Filters extends Categories {
	public $filters;
	
	public function __construct($newFilter=''){
		parent::__construct();

		////constructing filters array
		$filters = array("none" => "--No Filter--", "nocat" => "Uncategorized Photos");
		foreach($this -> getCats() as $cat){
			$filters[$cat['ID']]=$cat['name'];
		}
		
		$this -> filters = $filters;
		
		if(!$_SESSION) session_start();
		$this -> set_cur_filter($newFilter);
	}
	
	private function set_cur_filter($newFilter){
		if($newFilter != '' && array_key_exists($newFilter,$this -> filters)){
			if($newFilter == 'none'){
				unset($_SESSION['HCPhotos-filter']);
			} else {
				$_SESSION['HCPhotos-filter'] = $newFilter;
			}
		}
	}

	public function last(){
		if(isset($_SESSION['HCPhotos-filter'])){
			if($_SESSION['HCPhotos-filter'] == 'nocat'){
				$res = DB::query("select count(*) from photos where (cats = '[]' OR cats = '') and hide = 0");
			} else {
				$res = DB::query("select count(*) from photos where cats LIKE '%\"{$_SESSION['HCPhotos-filter']}\"%' and hide = 0");
			}
		} else {
			$res = DB::query("select count(*) from photos where hide = 0");
		}
		list($num) = $res -> fetch_row();
		return $num;
	}
	
	public function GetOptions(){
		foreach($this -> filters as $id => $name){
			$ret.= "<option ";
			if(isset($_SESSION['HCPhotos-filter']) && $_SESSION['HCPhotos-filter'] === "$id") $ret.= "selected ";
			$ret.= "value=\"$id\">$name</option>";
		}
		echo $ret;
	}
}