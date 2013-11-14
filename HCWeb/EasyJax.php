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
	
	public function __construct(){
		if(isset($_SERVER['PATH_INFO'])){
			$this -> path = $_SERVER['PATH_INFO'];
		}
		$this -> req_method = strtoupper($_SERVER['REQUEST_METHOD']);
		$this -> return_data = array();
		$this -> return_data['error'] = "";
		$this -> json_data = json_decode(file_get_contents("php://input"),1);
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
		header("Content-type: application/json; charset=UTF-8");
		header("Pragma: no-cache");
		header("Expires: Thu, 01 Dec 1997 16:00:00 GMT");
		echo json_encode($this->return_data);
		die;
	}

	public function db_execute($table,$auth_methods = array("GET","PUT","POST","DELETE")){
		if(!in_array($this -> req_method,$auth_methods)){
			$this -> add_error_msg("You are not authorized to do the requested action.");
			return false;
		}
		
		if($this -> path) $id = basename($this -> path);
		
		switch($this -> req_method){
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
			if($id = DB::insert($table,$this -> getData())){
				$this -> set_ret_data('id',$id);
			} else {
				$this -> add_error_msg("A new record could not be created.");
			}
			break;
	
		case "DELETE":
			if(!DB::delete($table,$id)){
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
