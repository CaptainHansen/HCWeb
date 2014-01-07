<?
namespace Photos;
use \HCWeb\DB;

class Compiler {
	private $mainphotos;
	private $photosizes=array(
		'l' => array("w" => "800", "h" => "600"),
		'm' => array("w" => "640", "h" => "480"),
		's' => array("w" => "320", "h" => "240"),
		'xs' => array("w" => "160", "h" => "120")
	);

	public function __construct($photosizes = false,$mainphotos = false){
		if(is_array($photosizes)) $this -> photosizes = $photosizes;
		if(!$mainphotos) $mainphotos = dirname($_SERVER['DOCUMENT_ROOT'])."/files/photos";
		$this -> mainphotos = $mainphotos;
	}


	private function strip_ext($listitem){
		$extlen=strlen(strrchr($listitem,"."));
		return substr($listitem,0,(strlen($listitem)-$extlen));
	}

	//THE KEY FOR THE 'pass' FIELD IN THE TABLE:
	//	-1 = Operation Failed
	//	 1 = Operation Passed
	//	 0 = n/a (message only contains information);
	//	 2 = Operation Warning
	//	-2 = Socket Error
	//	 3 = ONLY USE TO SIGNAL THE END OF A COMPILATION!!!

/*	private function log_result($msg,$pass=0){
		if($pass === true){
			$pass = 1;
		} elseif($pass === false){
			$pass = -1;
		}
		DB::query("insert into upload_log set timestamp = ".microtime(true).", msg = '$msg', pass = $pass");
		if($pass == -2) { //don't try another socket update if the reason for this message was a socket error (endless loop)
			return 0;
		} else {
			$this -> socket_send_update();
		}
	}
*/

	public function Run($file){
		$filename = basename($file);
		try {
			$photo = new \imagick($file);
		} catch (\ImagickException $e) {
			unlink($file);
			return false;
		}
		$photo -> setResourceLimit(6,1);

		$id = DB::insert('photos',array("time" => time(), 'cats' => '[]'));

		//rename file
		$expd=explode(".",$filename);
		$ext=$expd[(count($expd)-1)];
		$newn=$id.".$ext";
		rename($file,dirname($file)."/$newn");
//			$this -> log_result("Renaming \"$filename\" to \"$newn\" .... ",(rename($file,dirname($file)."/$newn")));

		$file = dirname($file)."/$newn";
		$filename=$newn;
		
		$geo = $photo -> getImageGeometry();
		$width = $geo['width'];
		$height = $geo['height'];
		
		$a_r = $width / $height;

		//update database
		DB::update("photos",$id,array("photo" => $filename, "asp_rat" => $a_r));
//			$this -> log_result("Updating database for ID {$id} .... ",(DB::getError() == ""));

		$normal = ($geo == NULL || $width > $height);
		$first=true;
		foreach($this -> photosizes as $dir => $size_data){
			if($normal) {
				//width is THIS PHOTO'S longest dimension
				$w = $size_data['w'];
				$h = $size_data['w']/$a_r;
			} else {
				//height is THIS PHOTO'S longest dimenstion
				$w = $size_data['w']*$a_r;
				$h = $size_data['w'];
			}
			$dest = $this->mainphotos."/{$dir}/{$filename}";
			if($first) {
				$pass = $photo -> resizeImage($w,$h,\Imagick::FILTER_CUBIC,true);
				$pass = $pass ? $photo -> writeImage($dest) : false;
//					$pass = (exec("convert -resize $photores \"$file\" \"$dest\"") == 0);
//					$this -> log_result("Resizing image \"$filename\" .... ",$pass);
				unlink($file);
//					$this -> log_result("Erasing original photo ..... ",(unlink($file)));
				$first = false;
			} else {
				$pass = $photo -> resizeImage($w,$h,\Imagick::FILTER_CUBIC,true);
				$pass = $pass ? $photo -> writeImage($dest) : false;
//					$pass = (exec("convert -resize $photores \"$file\" \"$dest\"") == 0);

//					$this -> log_result("Downsiziing image \"$filename\" for size \"$dir\" .... ",$pass);
			}
			$file = $dest;
		}

		///////HASH DATA ENTERING INTO DATABASE//////
		$hashObj = new Hash($file);
		$hash = $hashObj -> getHash();
		unset($hashObj);
		DB::update("photos",$id,array('hash' => $hash));
		$res = DB::query("select count(*) from photos where hash = \"$hash\"");
		list($cnt) = $res -> fetch_row();
		switch($cnt){
		case 0:
			$pass = -1;
			break;
		case 1:
			$pass = 1;
			break;
		default:
			$pass = 2;
		}
//			$this -> log_result("Calculating hash and checking for duplicates... ",$pass);
		$r = DB::query("select * from photos where ID = {$id}");
		return $r -> fetch_assoc();
	}
}
