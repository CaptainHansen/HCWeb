<?
namespace HCWeb;
use \HCWeb\EasyJax;

class Auth {
	private static $user_d=false;
	private static $login_page="/login.php";
	private static $loggedin = null;
	
	public static function setLoginPage($uri){
		self::$login_page = $uri;
	}

	public static function Init($redir = true){
		if(!isset($_SESSION)) session_start();
		if(!isset($_SESSION['HCAuth'])) {
			if($redir){
				self::Redirect("You are not logged in.  Log back in and try again.",self::$login_page);
			} else {
				self::$loggedin = false;
				return false;
			}
		}
		
		$auth_d = $_SESSION['HCAuth'];
		
		$res = DB::query("SELECT * FROM auth WHERE ID = '{$auth_d['ID']}' AND pass = '{$auth_d['pass']}'");
		if($d = $res -> fetch_assoc()) self::$user_d = $d;
		
		if(!self::$user_d){
			unset($_SESSION['HCAuth']);
			session_unset();
			if($redir){
				self::Redirect("Your session username and password are invalid.");
			} else {
				self::$loggedin = false;
				return false;
			}
		}

		if((date('U')-$auth_d['lastact']) >= 1800) {	//30 minute inactivity timeout
			unset($_SESSION['HCAuth']);
			session_unset();
			if($redir){
				self::Redirect("Your session has timed out.  You must log in again.",self::$login_page);
			} else {
				self::$loggedin = false;
				return false;
			}
		} else {
			$_SESSION['HCAuth']['lastact'] = date('U');
			DB::update('auth',$auth_d['ID'],array('lastact' => date('U'), 'ip_addr' => $_SERVER['REMOTE_ADDR']));
			if(defined('REQUIRE_ADMIN') && !(self::$user_d['admin'])){
				if($redir){
					self::Redirect("ACCESS DENIED - Admin priveleges required.");
				} else {
					self::$loggedin = false;
					return false;
				}
			}
		}
		self::$loggedin = true;
		return true;
	}
	
	public static function isLoggedIn(){
		if(self::$loggedin === null){
			self::Init(false);
		}
		return self::$loggedin;
	}

	public static function Login($user,$pass){
		$user = preg_replace("/'/i","\'",$user);
		$r = DB::query("select * from auth where user = '{$user}' and enabled = 1");
		$d = $r -> fetch_assoc();
		if(Password::Verify($pass,$d['pass'])){
			if(!isset($_SESSION)) session_start();
			$_SESSION['HCAuth'] = array('ID' => $d['ID'], 'pass' => $d['pass'], 'lastact' => date('U'));
			self::$user_d = $d;
			self::$loggedin = true;
			return true;
		} else {
			self::$loggedin = false;
			return false;
		}
	}

	public static function Logout(){
		if(!isset($_SESSION)) session_start();
		unset($_SESSION['HCAuth']);
		self::$loggedin = false;
		return true;
	}
	
	public static function getData($key=false){
		if(!$key){
			return self::$user_d;
		}
		if(isset(self::$user_d[$key])){
			return self::$user_d[$key];
		}
		return false;
	}

	public static function Redirect($msg,$redir="/"){
		if(defined('AJAX')){
			$easyj = new EasyJax();
			$easyj -> send_resp("You are not logged in.  Log back in and try again.");
		} elseif(defined("AUTH_STEALTH")) {
			$_SERVER['PATH_INFO'] = '/404';
			include("{$_SERVER['DOCUMENT_ROOT']}/error.php");
		} else {
			header("Location: $redir");
		}
		die;
	}
}
