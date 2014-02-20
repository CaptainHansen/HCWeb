<?
namespace Photos;
use \HCWeb\DB;

class Compiler {
	private static $mainphotos=false;
	private static $photosizes=array(
		'l' => array("d" => "1000"),
		's' => array("d" => "200", 'min' => true),
	);
	private static $full_size = false;

	public static function setOption($key,$val){
		switch($key){
		case "sizes":
			if(!is_array($val)) throw new \Exception("Photo size data must be an array.");
			self::$photosizes = $val;
			break;
		case "destination":
			self::$mainphotos = $val;
			break;
		case "full_size":
			if($val){
				self::$full_size = $val;
			} else {
				self::$full_size = false;
			}
			break;
		}
	}

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

		$file = dirname($file)."/$newn";
		$filename=$newn;
		
		$geo = $photo -> getImageGeometry();
		$width = $geo['width'];
		$height = $geo['height'];
		
		$a_r = $width / $height;

		//update database
		DB::update("photos",$id,array("filename" => $filename, "asp_rat" => $a_r));

		$normal = ($geo == NULL || $width > $height);
		$first=true;
		foreach(self::$photosizes as $dir => $size_data){
			if(isset($size_data['min']) && $size_data['min'] == true) $normal = !$normal;
			//$normal tells whether the width is greater than the height of the image.
			//default behavior is to pick the longest dimension, set that equal to new size dimension, and set the smallest dimension using the image's aspect ratio. (keeping a MAXIMUM image dimension)
			//if size_data['set_smallest'] is true, then the SMALLEST dimension in the photo will be set and the lONGEST dimension of the image will be resized based on the aspect ratio (keeping a MINIMUM image dimension).
			if($normal) {
				//width is THIS PHOTO'S longest dimension
				$w = $size_data['d'];
				$h = $size_data['d']/$a_r;
			} else {
				//height is THIS PHOTO'S longest dimenstion
				$w = $size_data['d']*$a_r;
				$h = $size_data['d'];
			}
			$dest = self::$mainphotos."/{$dir}/{$filename}";

			$pass = $photo -> resizeImage($w,$h,\Imagick::FILTER_CUBIC,true);
			$pass = $pass ? $photo -> writeImage($dest) : false;
				
		}
		if(self::$full_size){
			rename($file,self::$mainphotos."/".self::$full_size."/".$filename);
		} else {
			unlink($file);
		}

		///////HASH DATA ENTERING INTO DATABASE//////
		$hashObj = new Hash($dest);
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
		$r = DB::query("select * from photos where ID = {$id}");
		return $r -> fetch_assoc();
	}
}
