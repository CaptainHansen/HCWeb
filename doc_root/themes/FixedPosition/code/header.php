<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
use \HCWeb\Header;

echo "<title>".Header::$title."</title>";

if(\HCWeb\Auth::isLoggedIn()){
	Header::prependCssJs(THEME_RELPATH."/admin.css");
}

Header::prependCssJs("/js/jquery-1.8.3.min.js", "/js/EasyJax.js", THEME_RELPATH."/style.css");

Header::printCssJs();

echo "</head><body>";

include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div id=\"main\">";
