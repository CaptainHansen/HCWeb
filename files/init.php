<?
echo "Initializing New Website...\n";

if(!is_dir('newphotos')) mkdir("newphotos");
if(!is_dir('photos')) mkdir('photos');

if(!file_exists('private.key') || !file_exists('public.key')) {
	echo "Generating private/public key pairs for Encrypted EasyJax transmissions...\n";
	set_include_path(get_include_path().PATH_SEPARATOR.__DIR__."/APIs/phpseclib/");
	include('Crypt/RSA.php');
	$rsa = new Crypt_RSA();
	$d = $rsa -> createKey(1024);
	file_put_contents("private.key",$d['privatekey']);
	file_put_contents("public.key",$d['publickey']);
}

if(!file_exists('site.json')){
	echo "Writing JSON Site File with new encryption codes...\n";
	$d = json_decode(file_get_contents("site.json.dist"),true);

	$pass = "";
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./+_-)(*&^%$#@!;:?><,[]{}|";
	$lenchars=strlen($chars);
	for($i=0;$i<20;$i++){
		$pass.=substr($chars,rand() % $lenchars,1);
	}

	$d['site']['photos']['wmark_pass'] = $pass;

	file_put_contents("site.json",json_encode($d));
}

require(dirname(__DIR__)."/doc_root/bootstrap.php");
use \HCWeb\DB;

$r = DB::query("show tables");

echo "Initializing MySQL tables.\n";

$tables = array("auth","photos","photo_cats","pages");

while(list($table) = $r -> fetch_row()){
	if(FALSE !== ($k = array_search($table,$tables))){
		unset($tables[$k]);
	}
}

foreach($tables as $table){
	echo "Creating table {$table}\n";
	switch($table){
	
	case "auth":
		$get_d = array(
			array("user", "Enter a username of your choice",5),
			array("fname", "Enter your first name"),
			array("lname", "Enter your last name"),
			array("pass", "Enter a password",6),
		);
		
		$store = array();
		foreach($get_d as $gd){
			$done = false;
			list($id,$prompt) = $gd;
			while(!$done){
				echo $prompt.": ";
				$store[$id] = trim(fgets(STDIN));
				if(!$store[$id]) die("Aborting....\n");
				if(isset($gd[2])){
					if(strlen($store[$id]) < $gd[2]){
						echo "Your response must contain at least {$gd[2]} characters...\n";
					} else {
						$done = true;
					}
				} else {
					$done = true;
				}
			}
		}
		
		$store['pass'] = \HCWeb\Password::Hash($store['pass']);
		$store['admin'] = 1;
		$store['enabled'] = 1;
		
		DB::query("CREATE TABLE `auth` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `user` varchar(255) NOT NULL, `pass` varchar(34) NOT NULL, `admin` tinyint(1) NOT NULL, `enabled` tinyint(1) NOT NULL, `fname` varchar(255) NOT NULL, `lname` varchar(255) NOT NULL, `lastact` int(11) NOT NULL, `ip_addr` varchar(15) NOT NULL, PRIMARY KEY (`ID`), UNIQUE KEY `user` (`user`) )");
		DB::insert("auth",$store);
		break;
	
	case "photos":
		DB::query("CREATE TABLE `photos` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `filename` varchar(255) NOT NULL, `time` int(11) NOT NULL, `cats` mediumblob NOT NULL, `hash` char(16) NOT NULL, `hide` tinyint(1) NOT NULL, `asp_rat` double NOT NULL, PRIMARY KEY (`ID`) )");
		break;
	
	case "photo_cats":
		DB::query("CREATE TABLE `photo_cats` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, PRIMARY KEY (`ID`) )");
		break;
	
	case "pages":
		DB::query("CREATE TABLE `pages` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `html` mediumblob NOT NULL, `name` varchar(255) NOT NULL, `lastupd` int(11) NOT NULL, PRIMARY KEY (`ID`) )");
		break;
	
	default:
		echo "Table not recognized!\n";
	}
}

echo "Done!\n";
