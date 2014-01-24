<?
define("HC_PHOTO_UPLOADER",true);
require("../auth.php");
use \HCWeb\EasyJaxFiles;
use \Photos\Compiler;

$ejf = new EasyJaxFiles();

switch($ejf -> req_method){
case "POST":
	if($file = $ejf -> downloadTo(FILESROOT."/newphotos")){
		$ejf -> set_ret_data('orig_name',basename($file));
		$c = new Compiler();
		if(!($pdata = $c -> Run($file))){
			$ejf -> add_error_msg("This file could not be loaded by Imagick.");
			break;
		}
		$ejf -> set_ret_data('photo',$pdata);
	} else {
		$ejf -> add_error_msg("An error occurred - the file was not uploaded.");
	}
	$ejf -> send_resp();
	break;
	
default:
	$ejf -> add_error_msg("Request method not recognized.");
}
$ejf -> send_resp();