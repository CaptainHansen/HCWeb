<?
$dirs = array("./","./RGraph/");

$data = "";

$js = explode("/",$_SERVER['PATH_INFO']);

header("Content-type: text/plain");

foreach($js as $file){
	foreach($dirs as $dir){
		if(file_exists($dir.$file) && !is_dir($dir.$file)){
			$data.=file_get_contents($dir.$file);
		}
	}
}

$etag = md5($data);
if(isset($_SERVER['HTTP_IF_NONE_MATCH'])){
	if($_SERVER['HTTP_IF_NONE_MATCH'] == "\"$etag\""){
		header("HTTP/1.1 304 Not Modified");
		header("Etag: \"{$etag}\"");
		die;
	}
}

header("Content-type: text/plain");
header("Etag: \"{$etag}\"");

echo $data;
