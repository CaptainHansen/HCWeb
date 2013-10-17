<?
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

//* HCI public class
//* Written by Stephen Hansen, Copyright of Hansen Computers LLC,  2013
//* Used to assist with data exchange between client and server using JSON to transfer data.

class HCI {
	private $return_data;
	protected $mysqli_inst;
	protected $json_data;
	
	public function __construct($mysqli_inst="not_here"){
		$this -> return_data = array();
		$this -> return_data['error'] = "";
		$this -> mysqli_inst = $mysqli_inst;
		if(isset($_REQUEST['JSON_data'])){
			$this -> json_data = json_decode($_REQUEST['JSON_data'],1);
		} else {
			$this -> json_data = array();
		}
	}
	
	public function get_send_data($key=null){
		if($key === null){
			return $this -> json_data;
		} else {
			return $this -> json_data[$key];
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
		echo json_encode($this->return_data);
		die;
	}
	
	public function send_if_error(){
		if($return_data['error'] != ""){
			$this->send_resp();
		}
	}

	public function SQL_sub($sql){
		$mysqli = $this -> mysqli_inst;
		$mysqli->query($sql);
		if($mysqli->error != ""){
			$this -> add_error_msg($mysqli -> error);
		}
	}
}


///C2S = Client to Server
class HCI_C2S extends HCI {
	private $submitted_data;
	private $type;
	private $id;
	private $db;
	
	public function __construct($mysqli,$db,$text_ids=array(),$checkboxes=array()){
		parent::__construct($mysqli);
		$all_sent = $this -> json_data;	///this is sent directly from HCI_v1.js (decoded from JSON in parent class)
		$this -> submitted_data = $all_sent['save'];
		$this -> db = $db;

		$this -> set_type_and_id($all_sent['type'],$all_sent['ID']);
		
		$this -> escape_text($text_ids);
		$this -> check_checkboxes($checkboxes);
	}
	
	private function set_type_and_id($type,$id){
		$mysqli = $this -> mysqli_inst;
		$this -> type = $type;
		
		if($this -> type != 'l' && !isset($GLOBALS['user_d'])) {
			$this -> send_resp("You can't do that because you are not logged in.");
			die;
		}
		
		switch($this -> type){
			case 'c':	//creating a new record with this data.
			break;
			case 'e': $this -> id = $id;
			break;
			case 'd': $this -> id = $id;
			break;
			case 'l': $this -> id = $id;
			break;
			default: $this -> send_resp("The type of client to server transaction is invalid.");
		}
		
		if($this -> id > 0){
			$res = $mysqli -> query("select * from {$this->db} where ID = {$this->id}");
			if($res -> num_rows == 0){
				$this -> send_resp("The item you are trying to access does not exist.");
			}
		}
	}
	
	/***START OF Getters and Setters***/
	public function set_data($key,$data){
		$this -> submitted_data[$key] = $data;
		return true;
	}
	
	public function get_data($key){
		return $this -> submitted_data[$key];
	}
	
	public function get_type(){
		return $this -> type;
	}
	
	public function get_id(){
		return $this -> id;
	}
	
	/***END OF Getters and Setters***/
	
	private function escape_text($text_ids){
		foreach($text_ids as $id => $tid){
			$this -> submitted_data[$tid] = preg_replace('/\'/','\\\'',preg_replace('/&#039;/','\'',($this -> submitted_data[$tid])));
			if($this -> submitted_data[$tid] == null) unset($this-> submitted_data[$tid]);
		}
	}
	
	private function check_checkboxes($checkboxes){
		foreach($checkboxes as $id => $checkbox){
			if(isset($this->submitted_data[$checkbox])){
				if($this -> submitted_data[$checkbox] !== "true"){
					$this -> submitted_data[$checkbox] = 'false';
				}
			}
		}
	}

	public function write_to_SQL(){
		$mysqli = $this -> mysqli_inst;
	
		$sql=$this -> SQL_get_string();

		switch($this -> type){
		
		/////modifying or editing a record
		case 'e':
			if($mysqli->query($sql)){
				$this -> set_ret_data('ID',$this -> id);
//				if($mk_log) $this -> new_log_entry();
			} else {
				$this -> add_error_msg("Error editing ".$this->db." ID # ".$this->id);
			}
		break;
		
		//////creating a new record
		case 'c':
			if($mysqli->query($sql)){
				$id = $mysqli->insert_id;
				$this -> set_ret_data('ID',$id);
				$this -> id = $id;
//				if($mk_log) $this -> new_log_entry();
			} else {
				$this -> add_error_msg("Error adding to ".$this->db."\nSQL string: ".$sql);
			}
		break;
		
		//////and finally, deleting a record.
		case 'd':
			if($mysqli->query($sql)){
				$this -> set_ret_data('ID',$this -> id);
//				if($mk_log) $this -> new_log_entry();
			} else {
				$this -> add_error_msg("Error deleting from ".$this->db." ID # ".$this->id."\nSQL string: ".$sql);
			}
		break;
		
		///////loading a record
		case 'l':
			if($res = $mysqli->query($sql)){
				$this -> set_ret_data('ID',$this -> db);
				$this -> set_ret_data('data',$res->fetch_assoc());
			} else {
				$this -> add_error_msg("Error loading from ".$this->db." ID # ".$this->id);
			}
		break;
		}
	}
	
	private function SQL_get_string(){
		$db = $this -> db;
		switch($this -> type){
			case 'c': $sql="INSERT INTO $db SET ";
			break;
			case 'e': $sql="UPDATE $db SET ";
			break;
			case 'd': return "DELETE FROM $db WHERE ID = ".($this->id);
			case 'l': return "SELECT * FROM $db WHERE ID = ".($this->id);
		}
		$first=true;
		foreach($this -> submitted_data as $field => $value){
			if(!$first){
				$sql.=", ";
			} else {
				$first=false;
			}
			if($value == "true") {
				$sql.="$field = true";
			} elseif($value == "false") {
				$sql.="$field = false";
			} elseif($value == "") {
				$sql.="$field = NULL";
			} else {
				$sql.="$field = '$value'";
			}
		}
		if($this -> type == 'e') $sql.=" WHERE ID = '".$this -> id."'";
		return $sql;
	}
}

class HCI_SEQ extends HCI {
	private $to_id = 0;
	private $to_parent = 0;
	private $from_id = 0;
	private $db;
	private $pID_field;
	private $pID_mismatch_ok;
	
	
	public function __construct($mysqli,$db,$parent_ID_field='pID',$pID_mismatch_ok = false){	//if true, HCI_SEQ will move the item to the new pID, then do the move (bottom up)
		parent::__construct($mysqli);
		$all_sent = $this -> json_data;
		$this -> from_id = intval($all_sent['from_id']);
		if(isset($all_sent['to_id'])) $this -> to_id = intval($all_sent['to_id']);
		if(isset($all_sent['to_parent'])) $this -> to_parent = intval($all_sent['to_parent']);
		$this -> db = $db;
		$this -> pID_field = $parent_ID_field;
		$this -> pID_mismatch_ok = ($pID_mismatch_ok == true);	//true or false.
		
		$this -> set_ret_data('from_id',$this->from_id);
		$this -> set_ret_data('to_id',$this->to_id);
		$this -> set_ret_data('to_parent',$this -> to_parent);
		
		if(($this -> from_id == 0 || $this -> to_id == 0) && ($this -> from_id == 0 || $this -> to_parent == 0)){
			$this->send_resp("Javascript error: ID to move and/or ID of destination were not submitted.");
		}
	}
	
	public function write_seq_ch(){
		if($this -> to_parent > 0 && $this -> from_id > 0) {
			$this -> obj_to_parent();
		} else {
			$this -> obj_to_obj();
		}
	}
	
	private function obj_to_parent(){
		if(!$this -> pID_mismatch_ok) {
			$this -> send_resp("Parent ID mismatch is not enabled.  You cannot move the selected item to a different parent.");
		}
		
		$this -> set_ret_data('move','down');
	
		$mysqli = $this -> mysqli_inst;
		$res = $mysqli -> query("select seq,{$this->pID_field} from {$this->db} where ID = {$this->from_id}");
		list($from,$from_pID) = $res -> fetch_row();

		$to_pID = $this -> to_parent;

		if($from == 0){
			$this->send_resp("Sequence data could not be pulled from the database.");
		}
		
		//move to the bottom to remove from this sequence grouping
		$res = $mysqli -> query("select count(*) from {$this->db} where {$this->pID_field} = $from_pID");
		list($to_bottom) = $res -> fetch_row();
		if($from != $to_bottom){	//if the item is already at the bottom, take no action.
			$this -> internal_sequence_change($from,$to_bottom,$from_pID);
		}

		//it is now at the bottom.  change it's parent ID to where the destination object is, set seq to count of new grouping + 1
		$res = $mysqli -> query("select count(*) from {$this->db} where {$this->pID_field} = $to_pID");
		list($from) = $res -> fetch_row();
		$from++; //increment
		$mysqli -> query("update {$this->db} set seq = $from, {$this->pID_field} = $to_pID where ID = {$this->from_id}");
	}
	
	private function obj_to_obj(){
		$mysqli = $this -> mysqli_inst;
		$res = $mysqli -> query("select seq,{$this->pID_field} from {$this->db} where ID = {$this->from_id}");
		list($from,$from_pID) = $res -> fetch_row();
		
		$res = $mysqli -> query("select seq,{$this->pID_field} from {$this->db} where ID = {$this->to_id}");
		list($to,$to_pID) = $res -> fetch_row();
				
		if($to == 0 || $from == 0){
			$this->send_resp("Sequence data could not be pulled from the database.");
		}
		
		if($from_pID != $to_pID){	//implement sequence change on a pID mismatch (while preserving sequencing consistency)
			if($this -> pID_mismatch_ok){
				//move to the bottom to remove from this sequence grouping
				$res = $mysqli -> query("select count(*) from {$this->db} where {$this->pID_field} = $from_pID");
				list($to_bottom) = $res -> fetch_row();
				if($from != $to_bottom){	//if the item is already at the bottom, take no action.
					$this -> internal_sequence_change($from,$to_bottom,$from_pID);
				}

				//it is now at the bottom.  change it's parent ID to where the destination object is, set seq to count of new grouping + 1
				$res = $mysqli -> query("select count(*) from {$this->db} where {$this->pID_field} = $to_pID");
				list($from) = $res -> fetch_row();
				$from++; //increment
				$mysqli -> query("update {$this->db} set seq = $from, {$this->pID_field} = $to_pID where ID = {$this->from_id}");
				//now free to proceed as normal!
				
			} else {
				$this -> send_resp("The items you selected cannot be altered in sequence - parent ID mismatch.\n\nThis should NEVER happen...");
			}
		}
		$this -> internal_sequence_change($from,$to,$to_pID);
	}
	
	public function internal_sequence_change($from,$to,$pID){
		$mysqli = $this -> mysqli_inst;
		$sql = "update {$this->db} set seq = $to where id = {$this->from_id}";
		$mysqli -> query($sql);
		
		if($from == $to){
			$this -> send_resp("Selected item and relative item are identical.  No action required.");
		} elseif($from > $to){
			$this -> set_ret_data('move','up'); //use .insertBefore in jQuery
		} else {
			$this -> set_ret_data('move','down'); //use .insertAfter in jQuery
		}
		
		while($from > $to){  //moving UP, all others must move DOWN
			$sql = "update {$this->db} set seq = $from where id != {$this->from_id} and {$this->pID_field} = {$pID} and seq = ".($from-1);
			$mysqli -> query($sql);
//			$this -> add_error_msg($sql);
			$from--;
		}
		while($from < $to){  //moving DOWN, all others must move UP
			$sql="update {$this->db} set seq = $from where id != {$this->from_id} and {$this->pID_field} = {$pID} and seq = ".($from+1);
			$mysqli -> query($sql);
//			$this -> add_error_msg($sql);
			$from++;
		}
	}
}
?>
