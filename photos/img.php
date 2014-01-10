<?
require("../auth.php");
$id = basename($_SERVER['PATH_INFO']);

$photo = new \WMark\Photo(dirname($_SERVER['DOCUMENT_ROOT'])."/files/photos/l/");
$photo -> photoID = $id;
$photo -> opts = array('h' => 200);

$photo -> getImage();