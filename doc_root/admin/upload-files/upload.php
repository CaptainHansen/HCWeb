<?
define("AJAX",true);
require("../auth.php");
use \HCWeb\EasyJaxFiles;

$ejf = new EasyJaxFiles();
$ejf -> downloadTo(FILESROOT."/uploaded/");
$ejf -> send_resp();