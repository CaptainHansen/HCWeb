<?
require_once("../auth.php");
use \HCWeb\Header;
use \HCWeb\DB;
use \HCWeb\EasyJax;

$easyj = new EasyJax();

switch($easyj -> req_method){
case "GET":

	Header::addCssJs(THEME_RELPATH.'/admin-common.css');
	//Header::$title = "";
	Header::addCssJs('style.css');

	Header::addCssJs('/js/HCUI.js');
	Header::addCssJs('/js/HCUI-defaults.css');

	Header::addCssJs("Social.js");
	Header::setCurPage('adm_linkbar',"Social Media");
	include(THEMEHEAD);
	echo "<div id=\"left-column-color\"></div>";
	echo "<div class=\"column left\"><h1>Social Media</h1><p>This page allows you to easily edit the social media links on your website.  You can, remove, change the url, and change the order in which they appear by using this page.  To change in which they appear, simply drag and drop!</p></div>";

	echo "<div id=\"main-text\">";

	echo "<div id=\"social-links\">";
	echo "<div class=\"ref\"></div>";
	$s = new HCWeb\Social();
	$s -> setClass("social admin");
	$s -> fetchAll(true);
	echo $s;
	echo "<button style=\"vertical-align: center;\" onclick=\"Social.Add();\">Add Account</button>";
	echo "</div>";
	echo "</div>";


	echo "<div class=\"HC-blackout\"><div class=\"HC-ref\"></div><div id=\"SocialEdit\"></div></div>";


	echo "</div>";
	include(THEMEFOOT);
	die;

default:
	$easyj -> db_execute('social_media',array("PUT","POST","DELETE","SEQ"),"seq");
}

$easyj -> send_resp();