<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
use \HCWeb\Header;
Header::Init();

echo "<title>".Header::$title."</title>";

Header::printCssJs();

echo "</head>";
echo "<body>";

echo "<div class=\"header-wrapper\"><div class=\"header\"><h1>Herro!!</h1></div></div>";
include("{$_SERVER['DOCUMENT_ROOT']}/links.php");
echo "<div id=\"main\">";