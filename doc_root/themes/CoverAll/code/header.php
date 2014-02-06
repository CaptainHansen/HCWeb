<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
use \HCWeb\Header;

echo "<title>".Header::$title."</title>";

$themedir = "/".str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__DIR__));

if(\HCWeb\Auth::isLoggedIn()){
	Header::prependCssJs("/admin.css");
}

Header::prependCssJs("/style.css", "/js/jquery-1.8.3.js", "/js/EasyJax.js", $themedir."/style.css", $themedir."/theme.js");

Header::printCssJs();

echo "</head><body>";

echo "<div id=\"main-wrapper\"><div id=\"header\"><h1>Herro!!</h1></div></div>";
include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div id=\"main\">";