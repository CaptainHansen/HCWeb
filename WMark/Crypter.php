<?
namespace WMark;

class Crypter {
	private static $pass;
	private static $error_file;
	public $photoID;
	public $opts;
	public $gstring;
	
	public static function Init($pass,$error_file){
		self::$pass = $pass;
		self::$error_file = $error_file;
	}
	
	private function error($err,$data=array()){
		file_put_contents(self::$error_file,"ERROR: $err: ".date('F j Y h:i A')."\n\nc: ".$this->gstring."\n\n".implode("\n",$data)."\n------------\n\n",FILE_APPEND);
	}

	private function getRawData(){
		$opts = $this -> opts;
		$data = $this->photoID."\n";
		$data .= "{$opts['w']}\n";
		if(isset($opts['h'])) {
			$data .= "{$opts['h']}";
		}
		$data .= "\n";
		if(isset($opts['wmark']) && intval($opts['wmark']) > 0){
			$data .= intval($opts['wmark'])."\n";
			$data .= $opts['wmloc']."\n";
			if($opts['wmloc'] == 'm'){
				$data .= intval($opts['wmw'])."\n";
				$data .= intval($opts['wmh']);
			}
		}
		return $data;
	}
	
	public function encrypt(){
		$data = $this -> getRawData();
		$start=microtime(true);
		exec("echo -n \"$data\" | openssl enc -bf -e -pass pass:{self::$pass} -out /tmp/wm5-enc-$start.bin");
		$cdata=file_get_contents("/tmp/gdj-enc-$start.bin");
		unlink("/tmp/wm5-enc-$start.bin");
		
		$pd = unpack("H*",substr($cdata,8));
		$p = $pd[1];
//		$p = base64_encode(substr($cdata,8));
		$this -> gstring = $p;
		return $this -> gstring;
	}
	
	private function decrypt_d(){
		//part of file name created by rand()
		$startt = microtime(true);
		$rand = rand() % 1000;
		$startp = (int)(($startt - (int)($startt)) * 10000);
		$start = $startp ."_". $rand;
		
		$crypt="Salted__";
		$crypt.=pack("H*",$this->gstring);
//		$crypt.=base64_decode($this->gstring);
		file_put_contents("/tmp/gdj-crypt-$start.bin",$crypt);
		exec("openssl enc -d -bf -pass pass:{self::$pass} -in /tmp/wm5-crypt-$start.bin",$data,$retvar);
		unlink("/tmp/wm5-crypt-$start.bin");
		if($retvar != 0) {
			$this -> error("Decryption Failure",$data);
			return false;
		}
		return $data;
	}
	
	protected function decrypt(){
		if(!($data = $this -> decrypt_d())) return false;
		$this -> photoID = intval($data[0]);
		$opts = array();
		$opts['w'] = intval($data[1]);
		if(isset($data[2]) && $data[2] > 0) $opts['h'] = intval($data[2]);
		if(isset($data[3])){
			$opts['wmark'] = intval($data[3]);
			$opts['wmloc'] = $data[4];
			if($data[4] == 'm') { //watermark position set manually
				$opts['wmw'] = intval($data[5]);
				$opts['wmh'] = intval($data[6]);
			}
		}
		$this -> opts = $opts;
	}
}