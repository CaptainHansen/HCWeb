<?
namespace Photos;
use \HCWeb\DB;

class Categories {
	protected $categories;

	public function __construct($table = 'photo_cats') {
		$this -> categories = array();
		$res = DB::query("select * from {$table} order by name");
		while($cat = $res -> fetch_assoc()) {
			$this -> categories[] = $cat;
		}
	}
	
	public function getCats(){
		return $this -> categories;
	}
}
