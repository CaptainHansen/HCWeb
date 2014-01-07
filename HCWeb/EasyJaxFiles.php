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

//* EasyJaxFiles public class
//* Written by Stephen Hansen, Copyright of Hansen Computers LLC,  2013
//* Used to Upload data from a browser to the server asynchronously.

class EasyJaxFiles {
	private $return_data;
	public $path = false;
	public $req_method;

	public $filename;	
	private $destination_folder;
	public $exts = array();
	
	private $read;
	private $write;
	private $overw;
	
	public function __construct(){
		if(isset($_SERVER['PATH_INFO'])){
			$this -> path = $_SERVER['PATH_INFO'];
		}
		$this -> req_method = strtoupper($_SERVER['REQUEST_METHOD']);
		$this -> return_data = array();
		$this -> return_data['error'] = "";
	}
	
	public function downloadTo($folder = "/tmp/"){
		$headers = apache_request_headers();
		//$this -> type = $headers['Content-type'];
		$this -> filename = $headers['Filename'];
		$this -> read = fopen('php://input', "r");
		$this -> return_data['file'] = $this -> filename;
		
				
		$this -> destination_folder = $folder;
		if(file_exists($this -> destination_folder.DIRECTORY_SEPARATOR.$this -> filename)){
			$this -> set_ret_data('overw',true);
		} else {
			$this -> set_ret_data('overw',false);
		}
		$this -> write = fopen($this -> destination_folder.DIRECTORY_SEPARATOR.$this -> filename, "w");
		if(!$this -> write) return false;
		
		while(true) {
			$buffer = fgets($this -> read, 4096);
			if (strlen($buffer) == 0) {
				fclose($this -> read);
				fclose($this -> write);
				break;
			}
			fwrite($this -> write, $buffer);
		}
		return $this -> destination_folder.DIRECTORY_SEPARATOR.$this -> filename;
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
	
	public function send_if_error(){
		if($return_data['error'] != ""){
			$this->send_resp();
		}
	}
}