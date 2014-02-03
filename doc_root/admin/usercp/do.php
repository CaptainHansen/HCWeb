<?
define('REQUIRE_ADMIN',1);
define('AJAX',1);
require('../auth.php');
use \HCWeb\DB;
use \HCWeb\EasyJax;
use \HCWeb\Password;

$easyj = new EasyJax();

$id = basename($easyj -> path);

$minpasslength = 6;

if(!$easyj -> isSecure()) {
	$easyj -> send_resp("AJAX not secured.  Aborting.");
	die;
}

switch($easyj -> req_method){
case "GET":
	if($id != ""){
		$r = DB::query("select * from auth where ID = {$id}");
		$easyj -> set_ret_data('data',$r -> fetch_assoc());	
	} else {
		$r = DB::query("select * from auth");
		$data = array();
		while($d = $r -> fetch_assoc()){
			$data[$d['ID']] = $d;
		}
		$easyj -> set_ret_data('data',$data);
	}
	break;
	
case "PUT":
	if($id == ""){
		$easyj -> add_error_msg("ID number of record to modify was not supplied.");
		break;
	}
	if($id == \HCWeb\Auth::getData('ID')){
		$easyj -> add_error_msg("You cannot edit yourself using this page.");
		break;
	}
	$nd = $easyj -> getData();
	if(isset($nd['pass'])){
		if(strlen($nd['pass']) < $minpasslength){
			$easyj -> add_error_msg("The new password must be at least {$minpasslength} characters long.");
			break;
		}
		$crypt = Password::Hash($nd['pass']);
		$nd['pass'] = $crypt;
	}
	DB::update("auth",$id,$nd);
	break;

case "POST":
	$nd = $easyj -> getData();
	if($nd['user'] == ""){
		$easyj -> add_error_msg("You failed to specify a user name.");
		break;
	}
	if(strlen($nd['pass']) < $minpasslength){
		$easyj -> add_error_msg("The password must be at least {$minpasslength} characters long.");
		break;
	}
	$nd['pass'] = Password::Hash($nd['pass']);
	$nd['admin'] = 0;
	if(!($id = DB::insert('auth',$nd))){
		$easyj -> add_error_msg("Failed to add user.  This user might already exist.");
		break;
	}
	$easyj -> set_ret_data('id',$id);
	break;

case "DELETE":
	if($id == ""){
		$easyj -> add_error_msg("ID number of record to delete was not supplied.");
		break;
	}
	if($id == \HCWeb\Auth::getData('ID')){
		$easyj -> add_error_msg("Well this would've been really bad, wouldn't it? :-)");
		break;
	}
	DB::delete("auth",$id);
	break;

default:
	$easyj -> add_error_msg("Request method not recognized.");
	break;
}

$easyj -> send_resp();