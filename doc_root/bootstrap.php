<?
require_once(dirname(__DIR__)."/files/APIs/autoload.php");
define('FILESROOT',dirname(__DIR__)."/files");
use \HCWeb\DB;
if(file_exists(FILESROOT."/site.json")){
	$jdat = json_decode(file_get_contents(FILESROOT."/site.json"),true);
	if(isset($jdat['mysql'])) {
		$mysql = $jdat['mysql'];
		DB::Init($mysql['host'],$mysql['user'],$mysql['pass'],$mysql['db']);
		unset($jdat['mysql']);
		unset($mysql);
	}
	if($jdat['site']) {
		if(isset($jdat['site']['title'])) {
			\HCWeb\Header::$title = $jdat['site']['title'];
		}
		if(isset($jdat['site']['theme'])) {
			define("THEMEHEAD",__DIR__."/themes/{$jdat['site']['theme']}/code/header.php");
			define("THEMEFOOT",__DIR__."/themes/{$jdat['site']['theme']}/code/footer.php");
			define("THEME_NOTFOUND",__DIR__."/themes/{$jdat['site']['theme']}/code/404.php");
		}
		if(isset($jdat['site']['timezone'])){
			date_default_timezone_set($jdat['site']['timezone']);
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
	}
}
