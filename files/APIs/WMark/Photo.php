<?
namespace WMark;

class Photo extends Crypter {
	private static $photosdir;
	private static $wmarkdir;
	public $cacheDays;
	
	public static function Init($photosdir,$wmarkdir=false,$pass=false,$error_file=false){
		parent::Init($pass,$error_file);
		self::$photosdir = $photosdir;
		self::$wmarkdir = $wmarkdir;
	}
	
	public function __construct($cacheDays=1){
		$this -> cacheDays = $cacheDays;
	}
	
	private function setHeaders($etag,$mtime){
		$expires = $this -> cacheDays * 60 * 60 * 24;
		if($expires == 0){
			header("Pragma: no-cache");
			header("Expires: Sat. 26 Jul 1997 03:00:00 GMT");
			header("Cache-Content: no-cache, must-revalidate");
			header("ETag: $etag");
			header("Last Modified: ".gmdate('D, d M Y H;i:s', $mtime).' GMT');
		} else {

			//cache enable
			header("Pragma: public");
			header("Cache-control: max-age=".$expires);
			header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
			header("ETag: $etag");
			header("Last Modified: ".gmdate('D, d M Y H;i:s', $mtime).' GMT');
		
			if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mtime) {
				header("HTTP/1.1 304 Not Modified");
				die;
			}
			if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $etag == $_SERVER['HTTP_IF_NONE_MATCH']){
				header("HTTP/1.1 304 Not Modified");
				die;
			}
		}
	}
	
	private function findFile($file){
		$ret = false;
		if(is_array(self::$photosdir)){
			foreach(self::$photosdir as $dir){
				if(file_exists($dir."/".$file)){
					$ret = $dir."/".$file;
					break;
				}
			}
		} else {
			if(file_exists(self::$photosdir."/".$file)) $ret = self::$photosdir."/".$file;
		}
		return $ret;
	}
	
	public function getImage(){
		if(!is_array($this->opts) || $this->photoID <= 0) $this->decrypt();
		$opts = $this -> opts;

		$res = \HCWeb\DB::query("select * from photos where ID = ".$this->photoID);
		if(\HCWeb\DB::getError() != "") die;
		$pd = $res -> fetch_assoc();
		
		$etag = md5($pd['ID']."-".json_encode($this->opts));

		if(isset($opts['sz'])) {
//			if($opts['sz'] != 'f') $pd['filename'] = $pd['ID'].'.jpg';
			$file = $this -> findFile("{$opts['sz']}/{$pd['filename']}");
		} else {
			$file = $this -> findFile($pd['filename']);
		}

		if(!$file) {
			header("HTTP/1.1 404 Not Found");
			die;
		}
		
		$this -> setHeaders($etag,$pd['time']);	//execution should end here if a matching etag is found
		
		$photo = new \imagick($file);
		
		if(isset($opts['wh'])) {
			if($pd['asp_rat'] > 1){
				$opts['w'] = $opts['wh'];
			} else {
				$opts['h'] = $opts['wh'];
			}
			unset($opts['wh']);
		}
		
		////resizing using aspect ratio to calculate height of image (to preserve the aspect ratio)/////
		if(isset($opts['w']) && !isset($opts['h'])) $opts['h'] = ($opts['w']/$pd['asp_rat']);
		if(isset($opts['h']) && !isset($opts['w'])) $opts['w'] = ($opts['h']*$pd['asp_rat']);
		if(isset($opts['h']) && isset($opts['w'])){
			$photo -> resizeImage($opts['w'],$opts['h'],\Imagick::FILTER_CUBIC,true);
		}
		
		if(isset($opts['wmark']) && intval($opts['wmark']) > 0){
			$res = \HCWeb\DB::query("select * from wmarks where ID = ".intval($opts['wmark']));
			if(\HCWeb\DB::getError() != ""){
				die(\HCWeb\DB::getError());
			}
			$wdat = $res -> fetch_assoc();
			
			$wmark = new \imagick(self::$wmarkdir."/{$wdat['wmark']}");
			$wmark -> setImageFormat('png');
			
			if($opts['wmloc'] == "m"){
				$wstart = $opts['wmw'];
				$hstart = $opts['wmh'];
			} elseif($opts['wmloc'] != 'db' && $opts['wmloc'] != 'du') {
		
				$distance = 5;	//padding between watermark and edge of image (if positioned automatically)
				
				$wm = $wmark -> getImageGeometry();
				$right=$opts['w']-($wm['width']+$distance);
				$bottom=$opts['h']-($wm['height']+$distance);
				$left=$top=$distance;
				
				switch($opts['wmloc']){
					case "ul":
						$hstart=$top;
						$wstart=$left;
					break;
					case "ur":
						$hstart=$top;
						$wstart=$right;
					break;
					case "br":
						$hstart=$bottom;
						$wstart=$right;
					break;
					default:
						$hstart=$bottom;
						$wstart=$left;
					break;
				}
			} else {
				$wstart=$hstart=0;
				//////DIAGONALIZING THE WATERMARKKKKKK
				$wm = $wmark -> getImageGeometry();
				$w_ar = $wm['width'] / $wm['height'];
				$im = array('width' => $opts['w'], 'height' => $opts['h']);
				$i_ar = $pd['asp_rat'];
				if($w_ar <= $i_ar){
					//if aspect ratio of watermark is less than or equal to that of image, just resize the watermark to match image's height
					$new_size['height'] = $im['height'];
					$new_size['width'] = $new_size['height'] * $w_ar;
					$wmark -> resizeImage($new_size['width'],$new_size['height']);
				} else {
					//first, rotate the watermark to line up with the image's hypotenuse
					$rotate = (180*atan($im['height']/$im['width'])/pi());
					
					/* Angle settings:
					POSITIVE: top-left to bottom-right
					NEGATIVE: bottom-left to top-right
					*/
		
					if($opts['wmloc'] == 'db') $rotate = $rotate * -1;
					
					$backg = new \ImagickPixel('none');
					$wmark -> rotateImage($backg,$rotate);
			
					//next, resize the ROTATED watermark to match the dimensions of the image!!
					$wmark -> resizeImage($im['width'],$im['height'],\Imagick::FILTER_CUBIC,1);
				}
			}
			$photo -> compositeImage($wmark, \Imagick::COMPOSITE_OVER, $wstart,$hstart);
		}
		header("Content-type: image/jpeg");

		echo $photo;
	}
}
