<?
require("../auth.php");

if(!isset($_SESSION['HCPhotoChange'])) {
	header("Location: /");
	die;
} else {
//	$photo_ch = $_SESSION['HCPhotoChange'];
}

$title="Admin - Photo Management - {$_SESSION['HCPhotoChange']['title']}";
$head="<link rel=\"stylesheet\" href=\"style.css\" />
<script type=\"text/javascript\" src=\"HCPhotos.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Change.js\"></script>
<script type=\"text/javascript\" src=\"/js/EasyJax.js\"></script>";
$currentpage=$_SESSION['HCPhotoChange']['currentpage'];

include("{$_SERVER['DOCUMENT_ROOT']}/header.php");
echo "<div class=\"surround\">";

echo "<div class=\"HCPhotos-buttons\">";

echo "<button onClick=\"HCPhotos.Change.Select();\">Select</button>";
echo "<button onclick=\"HCPhotos.ClearSel('HCPhotos-main')\">Clear Selection</button>";

echo "<div class=\"center\" style=\"font-size: 14pt;\">Filter by Category <select id=\"filter\" onchange=\"HCPhotos.Filter()\">";
$filters = new Photos\Filters();
$filters -> GetOptions();
echo "</select></div>";

echo "</div>";
echo "<div class=\"HCPhotos-buttons-place\"></div>";

echo "<div class=\"center\"><select id=\"HCPhotos-page-select\" onchange=\"HCPhotos.Reload();\"></select></div>";
echo "<div id=\"HCPhotos-main\"></div>";

/*
if(isset($_SESSION['HCPhotoChange']['cur_photo'])){
	echo "<div class=\"admin-textblk\">Click on a photo below to change the photo you selected on the previous page.  The photo you select can be changed later.</div>";
	echo "<div class=\"center\">Current image:<br />";
	echo "<img class=\"portf\" src=\"/admin/smimage.php?id={$_SESSION['HCPhotoChange']['cur_photo']}\" alt=\"Current Photo\"></div>";
} else {
	echo "<div class=\"admin-textblk\">Click on a photo below to add it.  The photo you select can be changed later.</div>";
}
*/

echo "</div>";
include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");