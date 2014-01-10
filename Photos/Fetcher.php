<?
namespace Photos;

class Fetcher extends Filters {
	public $start;
	public $numToFetch;
	
	public function __construct($start,$numToFetch=50,$filter=''){
		parent::__construct($filter);
		$this -> start = $start;
		$this -> numToFetch = $numToFetch;
	}
	
	public function Fetch(){
		$res = \HCWeb\DB::query($this -> get_SQL()."limit {$this->start},{$this->numToFetch}");
		
		$return = array();
		if($res -> num_rows > 0){
			while($d = $res -> fetch_assoc()){
				$return[] = $d;
			}
		}
		return $return;
	}
	
	private function get_SQL(){
		if(isset($_SESSION['HCPhotos-filter'])){
			if($_SESSION['HCPhotos-filter'] == 'nocat'){
				return "select ID,cats from photos where (cats = '[]' OR cats = '') and hide = 0 order by time desc ";
			} else {
				return "select ID,cats from photos where cats LIKE '%\"{$_SESSION['HCPhotos-filter']}\"%' and hide = 0 order by time desc ";
			}
		} else {
			return "select ID,cats from photos where hide = 0 order by time desc ";
		}
	}
}