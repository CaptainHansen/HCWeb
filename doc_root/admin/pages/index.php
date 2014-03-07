<?
require("../auth.php");
use \HCWeb\Header;
use \HCWeb\DB;

Header::addCssJs(THEME_RELPATH.'/admin-common.css');
Header::addCssJs('/ckeditor/ckeditor.js');
Header::addCssJs('/ckeditor/adapters/jquery.js');
Header::addCssJs('HCPage.js');
Header::addCssJs('/js/date.js');
Header::setCurPage('adm_linkbar',"Edit Pages");
include("{$_SERVER['DOCUMENT_ROOT']}/header.php");

echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Edit Pages</h1><p>This page allows you to change certain text on different pages of this site.  Select a page from the drop-down menu below to get started.</p>";

$files = array();
$r = DB::query("select ID,name from pages order by name asc");

while(list($id,$name) = $r -> fetch_row()){
	$files[$id] = $name;
}

echo "<select id=\"page\" onchange=\"HCPage.Load();\">";
echo "<option value=\"--\">-- Select a Page --</option>";
foreach($files as $id => $name){
	echo "<option value=\"{$id}\">{$name}</option>";
}
echo "</select>";
echo "</div>";

echo "<div id=\"main-text\">";
echo "<div id=\"thestuff\" style=\"display: none;\"><textarea id=\"page_html\"></textarea><div class=\"center\"><button onclick=\"HCPage.Save()\">Save Changes</button></div></div>";
echo "</div>";

include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");