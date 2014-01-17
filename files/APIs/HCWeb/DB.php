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

class DB {
	private static $mysqli;

	public static function Init($host,$user,$pass,$db){
		self::$mysqli = mysqli_connect($host,$user,$pass,$db);
	}

	public static function getConn(){
		return self::$mysqli;
	}
	
	public static function query($sql){
		return self::$mysqli -> query($sql);
	}
	
	public static function update($table,$id,$data){
		$id = intval($id);
		$sql = "update $table set ";
		$first = true;
		foreach($data as $col => $val){
			if($first){
				$first = false;
			} else {
				$sql .= ", ";
			}
			$sql .= "$col = \"".preg_replace("/\"/","\\\"",$val)."\"";
		}
		$sql .= " where ID = $id";
		self::query($sql);
		return (self::getError() == "");
	}
	
	public static function insert($table,$data,$seq=false,$pid=false){
		if($seq){
			$sql = "select max({$seq}) from {$table}";
			if($pid) $sql .= " where {$pid} = {$data[$pid]}";
			$r = self::query($sql);
			list($mseq) = $r -> fetch_row();
			$data[$seq] = $mseq+1;
		}
	
		$sql = "insert into $table set ";
		$first = true;
		foreach($data as $col => $val){
			if($first){
				$first = false;
			} else {
				$sql .= ", ";
			}
			$sql .= "$col = \"".preg_replace("/\"/","\\\"",$val)."\"";
		}
		self::query($sql);
		if(self::getError() == ""){
			$ins_id = self::insert_id();
			if(!$ins_id) return true;
			return $ins_id;
		} else {
			return false;
		}
	}
	
	public static function delete($table,$id=false,$seq=false,$pid=false){
		if($id === false){
			$sql = "delete from $table";
		} else {
			$id = intval($id);
			if($seq){
				if($pid){
					$r = self::query("select {$pid} from {$table} where ID = {$id}");
					list($pidval) = $r -> fetch_row();
					$sql = "select ID from {$table} where {$pid} = {$pidval} and {$seq} = (select max({$seq}) from {$table} where {$pid} = {$pidval})";
				} else {
					$sql = "select ID from {$table} where {$seq} = (select max({$seq}) from {$table})";
				}
				$r = self::query($sql);
				list($toid) = $r -> fetch_row();
				self::sequence($table,$toid,$id,$seq,$pid);
			}
			$sql = "delete from $table where ID = $id";
		}
		self::query($sql);
		return (self::getError() == "");
	}
	
	public static function sequence($table,$toid,$fromid,$seq='seq',$pID=false){
		$toid = intval($toid);
		$fromid = intval($fromid);
		if($pID) {
			$r = DB::query("select {$seq},{$pID} from {$table} where ID = {$fromid}");
			list($from,$from_pid) = $r -> fetch_row();
			$r = DB::query("select {$seq},{$pID} from {$table} where ID = {$toid}");
			list($to,$to_pid) = $r -> fetch_row();
			if($from_pid != $to_pid) return false;
		} else {
			$r = DB::query("select {$seq} from {$table} where ID = {$fromid}");
			list($from) = $r -> fetch_row();
			$r = DB::query("select {$seq} from {$table} where ID = {$toid}");
			list($to) = $r -> fetch_row();
		}
		
		DB::update($table,$fromid,array($seq => $to));
		
		$ret = true;
		if($from == $to){
			return false;
		} elseif($from > $to){
			while($from > $to){  //moving UP, all others must move DOWN
				if($pID){
					$ret = $ret ? DB::query("update {$table} set $seq = $from where id != {$fromid} and {$pID} = {$from_pid} and $seq = ".($from-1)) : false;
				} else {
					$ret = $ret ? DB::query("update {$table} set $seq = $from where id != {$fromid} and $seq = ".($from-1)) : false;
				}
				$from--;
			}
//			return 'insertBefore';
		} else {
			while($from < $to){  //moving DOWN, all others must move UP
				if($pID){
					$ret = $ret ? DB::query("update {$table} set $seq = $from where id != {$fromid} and {$pID} = {$from_pid} and $seq = ".($from+1)) : false;
				} else {
					$ret = $ret ? DB::query("update {$table} set $seq = $from where id != {$fromid} and $seq = ".($from+1)) : false;
				}
				$from++;
			}
//			return 'insertAfter';
		}
		return $ret;
	}
	
	public static function getError(){
		return self::$mysqli -> error;
	}

	public static function insert_id(){
		return self::$mysqli -> insert_id;
	}
}