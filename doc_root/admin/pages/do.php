<?
define('AJAX',true);
require("../auth.php");
use \HCWeb\DB;
use \HCWeb\EasyJax;

$easyj = new EasyJax();

$file = basename($easyj -> path);

if($easyj -> req_method == 'PUT'){
	$easyj -> setData('lastupd',time());
}

$easyj -> db_execute('pages',array("GET","PUT"));
$easyj -> send_resp();	