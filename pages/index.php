<?
require("../auth.php");
$head = "<script src=\"/ckeditor/ckeditor.js\"></script>
<script src=\"/ckeditor/adapters/jquery.js\"></script>
<script type=\"text/javascript\" src=\"HCPage.js\"></script>";
include("{$_SERVER['DOCUMENT_ROOT']}/header.php");
echo "<div id=\"main-text\">";

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

echo "<div id=\"thestuff\"></div>";

echo "</div>";
include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");