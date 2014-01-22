<?
require_once(dirname(__DIR__)."/files/APIs/autoload.php");
define('FILESROOT',dirname(__DIR__)."/files");
use \HCWeb\DB;
if(file_exists(FILESROOT."/db.json")){
	$jdat = json_decode(file_get_contents(FILESROOT."/db.json"));
	DB::Init($jdat -> host,$jdat -> user,$jdat -> pass,$jdat -> db);
	unset($jdat);
}