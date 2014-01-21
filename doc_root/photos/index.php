<?
require("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");

$photo = basename($_SERVER['PATH_INFO']);
$size = basename(dirname($_SERVER['PATH_INFO']));

switch($size){
	case "full":
		$dir = "f";
		break;
	case "medium":
		$dir = "m";
		break;
	case "small":
	default:
		$dir = "s";
}

$wmarker = new \WMark\Photo();
$wmarker -> photoID = $photo;
$wmarker -> opts = array("sz" => $dir);

$wmarker -> getImage();