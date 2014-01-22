<?
require("bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?

if(!isset($title)) $title="New Website";	//your sites title (default)
echo "<title>$title</title>";

use \HCWeb\Auth;

if(Auth::isLoggedIn()) echo "<link rel=\"stylesheet\" href=\"/admin.css\" />";
echo "<link rel=\"stylesheet\" href=\"/style.css\" />";
echo "<script type=\"text/javascript\" src=\"/js/combine_js.php/jquery-1.8.3.js/EasyJax.js\"></script>";

if(isset($head)) echo $head;
echo "</head>";
echo "<body>";

echo "<div class=\"header-wrapper\"><div class=\"header\"><h1>Herro!!</h1></div></div>";
include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div class=\"main\">";