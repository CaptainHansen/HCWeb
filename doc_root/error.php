<?
require_once("bootstrap.php");

$code = false;
if(isset($_SERVER['PATH_INFO'])) {
	$code = basename($_SERVER['PATH_INFO']);
}

switch($code){
	case 404:
	default:
		header("HTTP/1.1 404 Not Found");
		include(THEME_NOTFOUND);
		break;
}