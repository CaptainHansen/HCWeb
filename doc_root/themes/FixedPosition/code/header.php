<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
//<meta name="viewport" content="width=device-width,minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0" />
use \HCWeb\Header;

if(\HCWeb\Auth::isLoggedIn()){
	Header::prependCssJs(THEME_RELPATH."/admin.css");
}
Header::prependCssJs("/js/jquery-1.8.3.min.js", "/js/EasyJax.js", "/defaults/common.css", THEME_RELPATH."/style.css", THEME_RELPATH."/links.css");

Header::printAll();
echo "</head><body>";

$social = new HCWeb\Social();
$social -> add("yelp");
$social -> add("facebook");
$social -> add("linkedin");
$social -> add("googleplus");
$social -> add("twitter");
$social -> add("instagram");
$social -> add("flickr");
$social -> add("youtube");
$social -> add("pinterest");
$social -> add("github","https://github.com/CaptainHansen");
$social -> add("tumblr");
echo $social;

include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div id=\"main\">";
