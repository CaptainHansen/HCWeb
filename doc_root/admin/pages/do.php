<?
define('AJAX',true);
require("../auth.php");
use \HCWeb\DB;
use \HCWeb\EasyJax;

$dir = FILESROOT."/pages/";

$easyj = new EasyJax();

$file = basename($easyj -> path);

switch($easyj -> req_method){
case "GET":
	if(file_exists($dir.$file)){
		$easyj -> set_ret_data('data',file_get_contents($dir.$file));
	} else {
		$easyj -> set_ret_data('data','');
	}
	break;

case "PUT":
	if(!file_put_contents($dir.$file,$easyj -> getData('data'))){
		$easyj -> add_error_msg("An error occurred saving the page data.");
	}
	break;

default:
	$easyj -> add_error_msg("Request method not supported.");
}
$easyj -> send_resp();
	