<?
namespace HCWeb;

class IPV4 {
	private $ip_text;
	private $ip_int;
	
	public function __construct($ip = false){
		if(!$ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		if(intval($ip) == $ip) {
			$this -> ip_int = $ip;
			$this -> ip_text = $this -> genText();
		} else {
			$this -> ip_text = $ip;
			$this -> ip_int = $this -> genInt();
		}
	}
	
	public function getInt(){
		return $this -> ip_int;
	}
	
	public function getText(){
		return $this -> ip_text;
	}
	
	public function getCountryName(){
		$mysqli = new \mysqli('localhost','weba_store','hcwebanalytics357','web_analytics');
		$res = $mysqli -> query("select country from ipv4 where start <= {$this->ip_int} and end >= {$this->ip_int}");
		$d = $res -> fetch_row();
		$mysqli -> close();
		if($d){
			return $d[0];
		} else {
			return false;
		}
	}
	
	
	private function genText(){	//taking 1293932 -> x.x.x.x
		$raw_int = pack("N",$this->ip_int);	//get hex representation: ff.ff.ff.ff
		$d = unpack("H8",$raw_int);
		
		$out = '';
		$first = true;
		for($i = 0; $i < 8; $i+=2){
			$out .= ($first) ? '' : '.';
			$first = false;
			$out .= hexdec(substr($d[1],$i,2));
		}
		return $out;
	}
	
	private function genInt(){
		$d = explode('.',$this -> ip_text);
		$d = array_reverse($d);
		for($i = 0; $i <= 3; $i++){
			$out += $d[$i]*(pow(256,$i));
		}
		return $out;
	}
}