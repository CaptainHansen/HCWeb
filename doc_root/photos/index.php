<?
require("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");

$photo = basename($_SERVER['PATH_INFO']);
$size = basename(dirname($_SERVER['PATH_INFO']));

$wmarker = new \WMark\Photo();

if($size == 'crypt'){
	$wmarker -> gstring = $photo;
	$wmarker -> getImage();
	die;
}

switch($size){
	case "large":
		$opts = array('sz' => 'l');
		break;
	case "small":
	default:
		$opts = array("sz" => 's');
		break;
}

$wmarker -> photoID = $photo;
$wmarker -> opts = $opts;
$wmarker -> getImage();