<?
define("REQUIRE_ADMIN",true);
define("AJAX",true);
require("../auth.php");
use \HCWeb\DB;

//DB::Init(HOST,USER,PASSWORD,DB);
$easyj = new \HCWeb\EasyJax();

$sql = $easyj -> getData('query');
/*
if(!DB::Init($d['host'],$d['username'],$d['password'],$d['db'])){
	$easyj -> send_resp("Could not log in.");
	die;
}*/

switch($_SERVER['REQUEST_METHOD']){
case "POST":
	$r = DB::query($sql);
	if(DB::getError() != "") {
		$easyj -> set_ret_data("dberr",DB::getError());
		$easyj -> send_resp();
		die;
	}
	if($r !== true){
		$first = true;
		$rows = array();
		while($d = $r -> fetch_assoc()){
			if($first){
				$cols = array();
				foreach($d as $id => $data){
					$cols[] = $id;
				}
				$first = false;
			}
			$row = array();
			foreach($d as $data){
				$row[] = htmlspecialchars($data);
			}
			$rows[] = $row;
		}
		$easyj -> set_ret_data('cols',$cols);
		$easyj -> set_ret_data('rows',$rows);
	}
	break;
default:
	$easyj -> add_error_msg("Invalid method.");
}

$easyj -> send_resp();