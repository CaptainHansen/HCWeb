<?
define("REQUIRE_ADMIN",true);
require("../auth.php");
use \HCWeb\Header;
Header::addCssJs("mysql.js");
Header::$currentpage = "MySQL";
require("{$_SERVER['DOCUMENT_ROOT']}/header.php");
?>
<style type="text/css">
div.credentials {
	text-align: center;
}
input#query {
	width: 500px;
}
div.query-results {
	font-family: Arial, sans-serif;
	font-size: 16px;
}
</style>
<div class="query-results">
<input type="text" id="query">
<div id="results"></div>
</div>
<?
echo \HCWeb\EasyJax::getPubKey();
require("{$_SERVER['DOCUMENT_ROOT']}/footer.php");