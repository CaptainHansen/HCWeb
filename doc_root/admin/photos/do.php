<?
define("AJAX",true);
require("../auth.php");
use \HCWeb\DB;
use \HCWeb\EasyJax;

function num_diff_bits($hash1,$hash2){
	$len = strlen($hash1);
	$cnt = 0;
	for($i = 0; $i < $len; $i++){
		if(substr($hash1,$i,1) != substr($hash2,$i,1)){
			$cnt++;
		}
	}
	return $cnt;
}

function add_cats($cats_json){
	$cats_vals = json_decode($cats_json,1);
	global $cats_keys;
	foreach($cats_vals as $id => $cat){
		$cats_keys[$cat]=true;
	}
}


$easyj = new EasyJax();

$id = basename($easyj -> path);

switch($easyj -> req_method){
case "RANGE":
	$start = intval($easyj -> getData('start'));
	$fetch = intval($easyj -> getData('more'));
	if($filter = $easyj -> getData('filter')){
		$fetcher = new \Photos\Fetcher($start,$fetch,$filter);
	} else {
		$fetcher = new \Photos\Fetcher($start,$fetch);
	}
	$easyj -> set_ret_data('count',$fetcher -> last());
	$easyj -> set_ret_data('photos',$fetcher -> Fetch());
	$easyj -> set_ret_data('cats',$fetcher -> getCats());
	break;

case "DELETE":
	if(!is_dir($filesroot)) {
		$easyj -> add_error_msg("Server side code failure - web files directory not specified.");
		break;
	}

	$ids = $easyj -> getData("ids");
	
	if($start = $easyj -> getData('start')) {
		$easyj -> add_error_msg("Javascript error: Last ID on page not specified!");
		break;
	}
	
	//NOW we delete the photos selected for deletion.
	$dirs = array();
	$rawscan = scandir($filesroot."/photos/");
	unset($rawscan[0]);
	unset($rawscan[1]);
	foreach($rawscan as $id => $dir){
		if(is_dir($filesroot."/photos/".$dir)) array_push($dirs,$dir);
	}
	
	foreach($ids as $i => $id){
		$res = DB::query("select photo from photos where ID = $id");
		if(DB::getError() != ""){
			$easyj -> add_error_msg("There was an error getting the filename from the database.  This should not happen, report this error.");
			break 2;
		}
		list($file) = $res -> fetch_row();
		foreach($dirs as $did => $dir){
			if(file_exists($filesroot."/photos/$dir/$file") && !unlink($filesroot."/photos/$dir/$file")){
				$easyj -> add_error_msg("Could not delete photos/$dir/$file");
			}
		}
		DB::query("delete from photos where photo = \"$file\"");	//with duplicate detection enabled, we need to delete all rows that reference the same file, since more than one ID may point to the same file.
	}
	
	$pfetch = new Photos\Fetcher($start,count($ids)+1);
	$easyj -> set_ret_data('photos',$pfetch -> Fetch());
	break;

case "CH_CATS":
	$res = DB::query("select * from photos where ID = {$id}");
	if(!($d = $res -> fetch_assoc())){
		$easyj -> add_error_msg('Javascript error - Either no ID was submitted or an invalid ID was submitted');
		break;
	}

	$easyj -> set_ret_data('id',$id);

	$cats = $easyj -> getData('cats');
	if($cats === false){
		$easyj -> add_error_msg('Javascript error - new categories list not submitted.');
		break;
	}
	
	$cats_json = json_encode($cats);

	if(!DB::update("photos",$id,array('cats' => $cats_json))){
		$easyj -> add_error_msg("There was a MySQL error the prevented data from being written.  Report this error.");
	}
	break;


case "DUP_CHECK":
	$r = DB::query("select * from photos where ID = \"{$id}\"");
	if(!$d = $r -> fetch_assoc()){
		$easyj -> add_error_msg("Invalid ID submitted.");
		break;
	}
	
	if(0){
		$res = DB::query("select * from photos where hash = \"{$d['hash']}\"");
		$dups = array();
		while($dup_d = $res -> fetch_assoc()){
			array_push($dups,$dup_d['ID']);
		}
	} else {
		$res = DB::query("select * from photos where hide = 0");
		$dups = array();
		$bin_hash = base_convert($d['hash'],16,2);
		while($pd = $res -> fetch_assoc()){
			$pd_bin_hash = base_convert($pd['hash'],16,2);
			$num_diff = num_diff_bits($bin_hash,$pd_bin_hash);
			if($num_diff < 5){
				$dups[] = $pd['ID'];
			}
		}
	}
	$easyj -> set_ret_data('dups',$dups);
	break;
	
case "MERGE":
	$ids = $easyj -> getData('ids');

	$r = DB::query("select * from photos where ID = {$id}");
	$keep_d = $r -> fetch_assoc();

	$dirs = scandir($filesroot."/photos/");
	unset($dirs[0]);
	unset($dirs[1]);


	$cats_keys = array();
	add_cats($keep_d['cats']);

	foreach($ids as $n => $id){
		if($keep_d['ID'] != $id){
			$id = (int)($id);
			$r = DB::query("select * from photos where ID = $id");
			$repl_d = $r -> fetch_assoc();
			add_cats($repl_d['cats']);
			
			//first delete the duplicate files
			foreach($dirs as $did => $dir){
				if(file_exists($filesroot."/photos/$dir/{$repl_d['photo']}") && !unlink($filesroot."/photos/$dir/{$repl_d['photo']}")){
					$easyj -> add_error_msg("Could not delete photos/$dir/$file");
				}
			}
		
			//now update the SQL database - need to RECURSIVELY point all selected duplicates at the most recent photo
			/* Example:
		
			selected:
		
			Photo 3 -> 3.JPG
			Photo 4 -> 4.JPG
			Photo 5 -> 5.JPG
		
			Photo 1 has already been evaluated as a duplicate of Photo 4:
		
			Photo 1 -> 4.JPG (and hidden)
		
			Photo 1 MUST NOW POINT TO 5.JPG INSTEAD OF 4.JPG BECAUSE IT WILL BE DELETED!!!!
		
			*/
			$sql = "update photos set photo = '{$keep_d['photo']}', hash = '{$keep_d['hash']}', hide = 1, cats = '[]', asp_rat = {$keep_d['asp_rat']} where ID = $id or photo = '{$repl_d['photo']}'";
			DB::query($sql);
		}
	}

	$cats = array();
	foreach($cats_keys as $key => $cat){
		$cats[] = strval($key);
	}

	DB::update('photos',array('cats' => json_encode($cats)));
	break;

case "PHOTO_CH":
	if(!isset($_SESSION['HCPhotoChange'])){
		$easyj -> set_ret_data('URL','/');
		$easyj -> add_error_msg('The session data has expired.  Please reload the page.');
		break;
	} else {
		$photo_ch = $_SESSION['HCPhotoChange'];
	}

	$id = $easyj -> getData('id');
	$sql = eval($photo_ch['SQL']);	//SQL in photo_ch MUST have a \$id where the new photo ID will reside.
	if(!DB::query($sql)){
		$easyj -> add_error_msg("Cannot use the selected photo.");
		break;
	}
	
	unset($_SESSION['HCPhotoChange']);  //doing this here, so I don't have to remember to put it in later.
	$easyj -> set_ret_data('URL',$photo_ch['URL']);
	break;
	
default:
	$easyj -> add_error_msg("Request method not supported.");
}

$easyj -> send_resp();