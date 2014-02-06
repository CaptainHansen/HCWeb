<?
require_once("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");
use \HCWeb\Header;
//Header::$title = "";
//Header::addCssJs('style.css');
//Header::$currentpage="Home";
include(THEMEHEAD);

echo "<h1>Oops...</h1><h2>The resource you're looking for cannot be found.</h2>";

include(THEMEFOOT);
