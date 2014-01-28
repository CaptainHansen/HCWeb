<?
echo "Initializing New Website...\n";

if(!is_dir('newphotos')) mkdir("newphotos");

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
if($r -> num_rows == 0){
	echo "Initializing MySQL tables.\n";
	DB::query("CREATE TABLE `auth` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `user` varchar(255) NOT NULL, `pass` varchar(34) NOT NULL, `admin` tinyint(1) NOT NULL, PRIMARY KEY (`ID`), UNIQUE KEY `user` (`user`) ) ");
	DB::query("CREATE TABLE `photos` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `filename` varchar(255) NOT NULL, `time` int(11) NOT NULL, `cats` mediumblob NOT NULL, `hash` char(16) NOT NULL, `hide` tinyint(1) NOT NULL, `asp_rat` double NOT NULL, PRIMARY KEY (`ID`) )");
	DB::query("CREATE TABLE `photo_cats` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, PRIMARY KEY (`ID`) )");
	DB::insert("auth",array("user" => 'shansen', 'admin' => 1, 'pass' => \HCWeb\Password::Hash('testpass')));
}

echo "Done!\n";
