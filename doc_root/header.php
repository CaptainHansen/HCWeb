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

echo "<link rel=\"stylesheet\" href=\"/style.css\" />";
echo "<script type=\"text/javascript\" src=\"/js/combine_js.php/jquery-1.8.3.js/EasyJax.js\"></script>";

if(isset($head)) echo $head;
echo "</head>";
echo "<body>";

echo "<div class=\"topimg-wrapper\"><div class=\"topimg\"><img class=\"topimg\" src=\"/css-images/header.jpg\" alt=\"Header Image\" /></div></div>";
include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div class=\"main\">";