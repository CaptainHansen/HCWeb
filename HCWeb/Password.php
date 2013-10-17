<?
namespace HCWeb;

class Password {
	public static function Hash($ptext){
		$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.";
        $salt='$1$';
        $lenchars=strlen($chars);
		for($i=0;$i<8;$i++){
			$salt.=substr($chars,rand() % $lenchars,1);
        }
        $salt.='$';
		return crypt($ptext,$salt);
	}
	
	public static function Verify($ptext,$hashed){
		$salt=substr($hashed,0,12);
		$crypt=crypt($ptext,$salt);
		if($crypt==$hashed){
			return true;
		} else {
			return false;
		}
	}
}