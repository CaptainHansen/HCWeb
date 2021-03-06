<?
require("../auth.php");
use \HCWeb\Header;

if(!isset($_SESSION['HCPhotoChange'])) {
	header("Location: /");
	die;
} else {
	$photo_ch = $_SESSION['HCPhotoChange'];
}

Header::$title="Admin - Photo Management - {$photo_ch['title']}";
Header::addCssJs(THEME_RELPATH.'/admin-common.css');
Header::addCssJs('style.css');
Header::addCssJs('/js/HCUI-defaults.css');
Header::addCssJs('/js/HC.Slider.js');
Header::addCssJs('HCPhotos.js');
Header::addCssJs('HCPhotos.Change.js');
Header::setCurPage($photo_ch['currentpage'][0],$photo_ch['currentpage'][1]);

include(THEMEHEAD);
echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>{$photo_ch['title']}</h1><p>{$photo_ch['descrip']}</p></div>";

if($photo_ch['DBA'] instanceof \HCWeb\DBInsert){
	$val = 1;
} else {
	$val = 0;
}
echo "<input type=\"hidden\" id=\"HCPhotos-change-select-multi\" value=\"{$val}\">";

echo "<div id=\"main-text\">";

echo "<div class=\"HCPhotos-buttons\">";

echo "<button onClick=\"HCPhotos.Change.Select();\">Select</button>";
echo "<button onclick=\"HCPhotos.ClearSel('HCPhotos-main')\">Clear Selection</button>";

echo "<div class=\"center\" style=\"font-size: 14pt;\">Filter by Category <select id=\"filter\" onchange=\"HCPhotos.Filter()\">";
$filters = new Photos\Filters();
$filters -> GetOptions();
echo "</select></div>";

echo "<input type=\"HCSlider\" id=\"HCPhotos-size\" value=\"0\">";

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
include(THEMEFOOT);
