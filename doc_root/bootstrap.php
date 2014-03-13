<?
require_once(dirname(__DIR__)."/files/APIs/autoload.php");
define('FILESROOT',dirname(__DIR__)."/files");
$domain = $_SERVER['SERVER_NAME'];
if(preg_match("/[^\.]+\.[^\.]+$/",$_SERVER['SERVER_NAME'],$matches)) {
	$domain = $matches[0];
}
define('DOMAIN',$domain);
unset($domain);
use \HCWeb\DB;

if(file_exists(FILESROOT."/site.json")){
	$jdat = json_decode(file_get_contents(FILESROOT."/site.json"),true);
	if(isset($jdat['mysql'])) {
		$mysql = $jdat['mysql'];
		DB::Init($mysql['host'],$mysql['user'],$mysql['pass'],$mysql['db']);
		unset($jdat['mysql']);
		unset($mysql);
	}

	if(isset($jdat['site']['title'])) {
		\HCWeb\Header::$title = $jdat['site']['title'];
	}
	if(isset($jdat['site']['theme'])) {
		define("THEME_ABSPATH",__DIR__."/themes/{$jdat['site']['theme']}");

		$themedir = str_replace($_SERVER['DOCUMENT_ROOT'],'',THEME_ABSPATH);
		if(substr($themedir,0,1) != '/') $themedir = '/'.$themedir;
		define("THEME_RELPATH",$themedir);
		
		define("THEMEHEAD",THEME_ABSPATH."/code/header.php");
		define("THEMEFOOT",THEME_ABSPATH."/code/footer.php");
		define("THEME_NOTFOUND",THEME_ABSPATH."/code/404.php");
	}
	if(isset($jdat['site']['timezone'])){
		date_default_timezone_set($jdat['site']['timezone']);
	}
	if(isset($jdat['site']['currency'])){
		setlocale(LC_MONETARY,$jdat['site']['currency']);
	}
	if(isset($jdat['site']['photos'])) {
		$photos = $jdat['site']['photos'];
		if(isset($photos['destination'])){
			if(is_array($photos['destination'])){
				foreach($photos['destination'] as $id => $dest){
					$photos['destination'][$id] = preg_replace("/\{FILESROOT\}/",FILESROOT,$dest);
				}
				define('HC_PHOTOSDIR',$photos['destination'][0]);
				\WMark\Photo::Init($photos['destination']);
			} else {
				define('HC_PHOTOSDIR',preg_replace("/\{FILESROOT\}/",FILESROOT,$photos['destination']));
				\WMark\Photo::Init(HC_PHOTOSDIR);
			}
		}
		if(defined('HC_PHOTO_UPLOADER')) {
			\Photos\Compiler::setOption('destination',HC_PHOTOSDIR);
			\Photos\Compiler::setOption('full_size',$photos['full_size']);
			\Photos\Compiler::setOption('sizes',$photos['sizes']);
		}
		if(isset($photos['wmark_pass'])){
			if(isset($photos['wmark_err_file'])){
				\WMark\Crypter::Init($photos['wmark_pass'],$photos['wmark_err_file']);
			} else {
				\WMark\Crypter::Init($photos['wmark_pass']);
			}
		}
		unset($photos);
	}

	if(isset($jdat['auth_stealth']) && $jdat['auth_stealth']){
		define('AUTH_STEALTH',true);
	}
}
