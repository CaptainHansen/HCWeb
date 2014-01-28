<?
namespace HCWeb;

/*
 * Copyright (c) 2013 Stephen Hansen (www.hansencomputers.com)
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:

 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE. 
 */

//* EasyJax public class
//* Written by Stephen Hansen, Copyright of Hansen Computers LLC,  2013
//* Used to assist with data exchange between client and server using JSON to transfer data.

class EasyJax {
	private $return_data;
	protected $json_data;
	public $path = false;
	public $req_method;
	private $aes = false;
	
	public function __construct(){
		if(isset($_SERVER['PATH_INFO'])){
			$this -> path = $_SERVER['PATH_INFO'];
		}
		$this -> req_method = strtoupper($_SERVER['REQUEST_METHOD']);
		$this -> return_data = array();
		$this -> return_data['error'] = "";
		if(isset($_SERVER['HTTP_ENCRYPTION_KEY'])) {
			$cipherkey = $_SERVER['HTTP_ENCRYPTION_KEY'];
			include("Crypt/RSA.php");
			$rsa = new \Crypt_RSA();
			$rsa -> loadKey(file_get_contents(FILESROOT."/private.key"));
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$ks = $rsa -> decrypt(base64_decode($cipherkey));
			preg_match("/^(.*)---(.*)$/",$ks,$matches);
			include("Crypt/AES.php");
			$this -> aes = new \Crypt_AES();
			$this -> aes -> setKey(base64_decode($matches[1]));
			$this -> aes -> setIV(base64_decode($matches[2]));
			$jtext = $this -> aes -> decrypt(base64_decode(file_get_contents("php://input")));
//			echo $jtext."\n\n";
		} else {
			$jtext = file_get_contents("php://input");
		}
		$this -> json_data = json_decode($jtext,1);
	}
	
	public function isSecure(){
		if($this -> aes){
			return true;
		}
		return false;
	}
	
	public static function getPubKey(){
		include("Crypt/RSA.php");
		$rsa = new \Crypt_RSA();
		$rsa -> loadKey(file_get_contents(FILESROOT."/public.key"));
		$m = $rsa -> modulus -> toHex();
		$e = $rsa -> exponent -> toHex();
		return "<input type=\"hidden\" id=\"EasyJaxKey\" value='".json_encode(array('m' => $m, 'e' => $e))."'>";
	}
	
	public function getData($key=null){
		if($key === null){
			return $this -> json_data;
		} else {
			if(isset($this -> json_data[$key])){
				return $this -> json_data[$key];
			} else {
				return false;
			}
		}
	}
	
	public function setData($key,$val){
		if(!isset($this -> json_data[$key])){
			$this -> json_data[$key] = $val;
			return true;
		}
		return false;
	}
	
	public function set_ret_data($key,$data){
		$this -> return_data[$key] = $data;
	}
	
	public function add_error_msg($msg){
		$this -> return_data['error'] .= $msg."\n";
	}


	/////Returning data to client
	public function send_resp($error = ""){
		if($error != ""){
			$this -> add_error_msg($error);
		}
		if($this -> aes){
			header("Content-type: application/octet-stream;");
			header("Content-Transfer-Encoding: base64;");
			$send = $this -> aes -> encrypt(json_encode($this -> return_data));
//			$send = json_encode($this -> return_data);
			$send = base64_encode($send);
		} else {
			header("Content-type: application/json; charset=UTF-8");
			$send = json_encode($this -> return_data);
		}
		header("Pragma: no-cache");
		header("Expires: Thu, 01 Dec 1997 16:00:00 GMT");
		echo $send;
		die;
	}

	public function db_execute($table,$auth_methods = array("GET","PUT","POST","DELETE","SEQ"),$seq=false,$pid=false){
		//seq and pid are for needed for sequencing information
		if(!in_array($this -> req_method,$auth_methods)){
			$this -> add_error_msg("You are not authorized to do the requested action.");
			return false;
		}
		
		if($this -> path) $id = intval(basename($this -> path));
		
		switch($this -> req_method){
		case "SEQ":
			if(!$seq){
				$this -> add_error_msg("Sequence change operation requested but no sequence column name set on the server side.");
				break;
			}
			if(!DB::sequence($table,intval($this -> getData('toid')),$id,$seq,$pid)){
				$this -> add_error_msg("Sequence change failed.");
			}
			break;
		case "GET":
			if($r = DB::query("select * from {$table} where ID = {$id}")) {
				$this -> set_ret_data('data',$r -> fetch_assoc());
			} else {
				$this -> add_error_msg("Could not load the requested data.");
			}
			break;

		case "PUT":
			if(!DB::update($table,$id,$this -> getData())){
				$this -> add_error_msg("Save could not be completed");
			}
			break;

		case "POST":
			if($id = DB::insert($table,$this -> getData(),$seq,$pid)){
				$this -> set_ret_data('id',$id);
			} else {
				$this -> add_error_msg("A new record could not be created.");
			}
			break;
	
		case "DELETE":
			if(!DB::delete($table,$id,$seq,$pid)){
				$this -> add_error_msg("Record {$id} could not be deleted");
			}
			break;
		
		default:
			$this -> add_error_msg("Request method not recognized.");

		}
	}
	
	
	public function send_if_error(){
		if($return_data['error'] != ""){
			$this->send_resp();
		}
	}
}
