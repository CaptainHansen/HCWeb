<?
require("../auth.php");
$head = "<link rel=\"stylesheet\" href=\"/admin/common.css\" />
<script src=\"/ckeditor/ckeditor.js\"></script>
<script src=\"/ckeditor/adapters/jquery.js\"></script>
<script type=\"text/javascript\" src=\"HCPage.js\"></script>";
$currentpage = "Edit Pages";
include("{$_SERVER['DOCUMENT_ROOT']}/header.php");

echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Edit Pages</h1><p>This page allows you to change certain text on different pages of this site.  Select a page from the drop-down menu below to get started.</p>";

$files = array(
	"Home" => "home.txt",
	"About Us" => "about.txt",
	"Services - Investments" => 'investments.txt',
	'Services - Property Management' => 'prop_management.txt',
	'Services - Construction' => 'construction.txt',
	"Conact Us" => "contact.txt",
);

echo "<select id=\"page\" onchange=\"HCPage.Load();\">";
echo "<option value=\"--\">-- Select a Page --</option>";
foreach($files as $name => $file){
	echo "<option value=\"{$file}\">{$name}</option>";
}
echo "</select>";
echo "</div>";

echo "<div id=\"main-text\">";
echo "<div id=\"thestuff\" style=\"display: none;\"><textarea id=\"file_contents\"></textarea><div class=\"center\"><button onclick=\"HCPage.Save()\">Save Changes</button></div></div>";
echo "</div>";

include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");