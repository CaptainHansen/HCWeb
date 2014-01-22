<?
require(dirname(__DIR__)."/doc_root/bootstrap.php");
use \HCWeb\DB;

mkdir("newphotos");

$r = DB::query("show tables");
if($r -> num_rows == 0){
	DB::query("CREATE TABLE `auth` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `user` varchar(255) NOT NULL, `pass` varchar(34) NOT NULL, `admin` tinyint(1) NOT NULL, PRIMARY KEY (`ID`), UNIQUE KEY `user` (`user`) ) ");
	DB::query("CREATE TABLE `photos` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `photo` text NOT NULL, `time` int(11) NOT NULL, `cats` text NOT NULL, `hash` char(16) NOT NULL, `hide` tinyint(1) NOT NULL, `asp_rat` double NOT NULL, PRIMARY KEY (`ID`) )");
	DB::query("CREATE TABLE `photo_cats` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, PRIMARY KEY (`ID`) )");
	DB::insert("auth",array("user" => 'shansen', 'admin' => 1, 'pass' => \HCWeb\Password::Hash('testpass')));
}

