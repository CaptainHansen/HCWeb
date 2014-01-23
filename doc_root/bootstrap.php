<?
require_once(dirname(__DIR__)."/files/APIs/autoload.php");
define('FILESROOT',dirname(__DIR__)."/files");
use \HCWeb\DB;
if(file_exists(FILESROOT."/site.json")){
	$jdat = json_decode(file_get_contents(FILESROOT."/site.json"));
	if($jdat -> mysql) {
		$mysql = $jdat -> mysql;
		DB::Init($mysql -> host,$mysql -> user,$mysql -> pass,$mysql -> db);
		unset($jdat -> mysql);
		unset($mysql);
	}
	if($jdat -> site) {
		if(isset($jdat -> site -> theme)) {
			\HCWeb\Header::addCssJs("/themes/{$jdat -> site -> theme}/style.css");
			if(file_exists(__DIR__."/themes/{$jdat -> site -> theme}/theme.js")){
				\HCWeb\Header::addCssJs("/themes/{$jdat -> site -> theme}/theme.js");
			}
			define("THEMEHEAD",__DIR__."/themes/{$jdat -> site -> theme}/code/header.php");
			define("THEMEFOOT",__DIR__."/themes/{$jdat -> site -> theme}/code/footer.php");
		}
	}
}