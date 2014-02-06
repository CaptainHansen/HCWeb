<?
require("bootstrap.php");

$code = false;
if(isset($_SERVER['PATH_INFO'])) {
	$code = basename($_SERVER['PATH_INFO']);
}

switch($code){
	case 404:
	default:
		include(THEME_NOTFOUND);
		break;
}