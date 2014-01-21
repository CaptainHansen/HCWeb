<?
namespace HCWeb;
use \HCWeb\EasyJax;

class Auth {
	private static $user_d=false;
	private static $login_page="/login.php";
	private static $loggedin = false;
	
	public static function setLoginPage($uri){
		self::$login_page = $uri;
	}

	public static function Init($redir = true){
		if(!isset($_SESSION)) session_start();
		if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])){
			if($redir){
				self::Redirect("You are not logged in.  Log back in and try again.",self::$login_page);
			} else {
				return false;
			}
		}
		
		$res = DB::query("SELECT * FROM auth WHERE user = '{$_SESSION['user']}' AND pass = '{$_SESSION['pass']}'");
		if($d = $res -> fetch_assoc()) self::$user_d = $d;
		
		if(!self::$user_d){
			unset($_SESSION['user']);
			unset($_SESSION['pass']);
			unset($_SESSION['lastact']);
			session_unset();
			if($redir){
				self::Redirect("Your session username and password are invalid.");
			} else {
				return false;
			}
		}

		if((date('U')-$_SESSION['lastact']) >= 1800) {	//30 minute inactivity timeout
			unset($_SESSION['user']);
			unset($_SESSION['pass']);
			unset($_SESSION['lastact']);
			session_unset();
			if($redir){
				self::Redirect("Your session has timed out.  You must log in again.",self::$login_page);
			} else {
				return false;
			}
		} else {
			$_SESSION['lastact'] = date('U');
			if(defined('REQUIRE_ADMIN') && !(self::$user_d['admin'])){
				if($redir){
					self::Redirect("ACCESS DENIED - Admin priveleges required.");
				} else {
					return false;
				}
			}
		}
		self::$loggedin = true;
		return true;
	}
	
	public static function isLoggedIn(){
		return self::$loggedin;
	}

	public static function Login($user,$pass){
		$user = preg_replace("/'/i","\'",$user);
		$r = DB::query("select * from auth where user = '{$user}'");
		$d = $r -> fetch_assoc();
		if(Password::Verify($pass,$d['pass'])){
			if(!isset($_SESSION)) session_start();
			$_SESSION['user'] = $d['user'];
			$_SESSION['pass'] = $d['pass'];
			$_SESSION['lastact'] = date('U');
			self::$user_d = $d;
			self::$loggedin = true;
			return true;
		} else {
			return false;
		}
	}

	public static function Logout(){
		if(!isset($_SESSION)) session_start();
		unset($_SESSION['user']);
		unset($_SESSION['pass']);
		unset($_SESSION['lastact']);
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
		} else {
			header("Location: $redir");
		}
		die;
	}
}
