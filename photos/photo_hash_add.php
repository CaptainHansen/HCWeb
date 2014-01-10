<?
require("../auth.php");
header("Content-type: text/plain");
use \HCWeb\DB;

$r = DB::query("select * from photos where hash = \"\"");
while($d = $r -> fetch_assoc()) {
	$hash = new Photos\Hash($filesroot."/photos/l/".$d['photo']);
	$h = $hash -> getHash();
	DB::update("photos",$d['ID'],array("hash" => $h));
	echo $h."\n";
}