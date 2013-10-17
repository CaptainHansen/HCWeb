<?
class AnalyticsLogger {
	private $domain_ID;
	private $mysqli;
	private $entry;
	
	public function __construct($domain_name){
		$mysqli = mysqli_connect('localhost','weba_store','hcwebanalytics357','web_analytics');
//		if(!$mysqli) return false;
		$this -> mysqli = $mysqli;
		$res = $mysqli -> query("select * from websites where domain_name = '$domain_name'");
		echo $mysqli -> error;
		$d = $res -> fetch_assoc();
		$this -> domain_ID = $d['ID'];
		$this -> entry = false;
	}
	
	public static function log_hit($domain_name=""){
		$inst = new AnalyticsLogger($domain_name);
		$inst -> mk_entry();
	}
	
	public function mk_entry(){
		if(!$this -> entry){
			$sql = "insert into raw_data set domain = {$this->domain_ID}, date = ".date('U');
			if(isset($_SERVER['HTTP_REFERER'])) $sql.=", ref = \"".preg_replace("/\"/i","\\\"",$_SERVER['HTTP_REFERER'])."\"";
			$sql .=", user_agent = \"".preg_replace("/\"/i","\\\"",$_SERVER['HTTP_USER_AGENT'])."\", ip_addr = '{$_SERVER['REMOTE_ADDR']}', url = \"{$_SERVER['REQUEST_URI']}\"";
			$this->mysqli->query($sql);
			$this -> entry = true;
			return true;
		} else {
			return false;
		}
	}
}
?>