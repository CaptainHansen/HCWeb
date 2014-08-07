<?
require("{$_SERVER['DOCUMENT_ROOT']}/admin/auth.php");
use \HCWeb\DB;
use \HCWeb\Header;

Header::addCssJs(THEME_RELPATH.'/admin-common.css');
Header::addCssJs('style.css');
Header::addCssJs('/js/HC.Progress.js');
Header::addCssJs('/js/HC.Slider.js');
Header::addCssJs('HCPhotos.js');
Header::addCssJs('HCPhotos.Cats.js');
Header::addCssJs('HCPhotos.Duplicates.js');
Header::addCssJs('HCPhotos.Upload.js');
Header::addCssJs('/js/EasyJaxFiles.js');
Header::addCssJs('/js/jquery.waitforimages.js');
Header::addCssJs('/js/HCUI.js');
Header::addCssJs('/js/HCUI-defaults.css');
Header::$title="Photo Management";
Header::setCurPage('adm_linkbar',"Manage Photos");
include(THEMEHEAD);

echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Photo Manager</h1><p>This page shows all photos uploaded to this site and allows control over what photos show up on the Home page of the site.  Duplicates can be easily resolved, photos can be removed (deleting a photo here also deletes it from the portfolio section of the website), and multiple photos can be uploaded at the same time.</p></div>";

echo "<div id=\"main-text\">";

echo "<div class=\"HCPhotos-buttons\">";

echo "<button onClick=\"HCPhotos.Delete();\">Delete Selected Photos</button>";
echo "<div style=\"width: 0; height: 0; position: absolute;\"><input type=\"file\" id=\"HCPhotos-upload\" multiple=\"multiple\"></div><button onclick=\"$('#HCPhotos-upload').trigger('click');\">Upload Photos</button>";
echo "<button onClick=\"HCPhotos.Duplicates.Show();\">Show Duplicates</button>";
echo "<button onclick=\"HCPhotos.ClearSel('HCPhotos-main');\">Clear Selection</button>";


echo "<div class=\"center\" style=\"font-size: 14pt;\">Filter by Category <select id=\"filter\" onchange=\"HCPhotos.Filter()\">";
$filters = new Photos\Filters();
$filters -> GetOptions();
echo "</select></div>";

echo "<input type=\"HCSlider\" id=\"HCPhotos-size\" value=\"0\">";

echo "</div>";
echo "<div class=\"HCPhotos-buttons-place\"></div>";

echo "<div class=\"center\"><select id=\"HCPhotos-page-select\" onchange=\"HCPhotos.Reload();\"></select></div>";
echo "<div id=\"HCPhotos-main\"></div>";

echo "<div id=\"HCPhotos-upload-blackout\" class=\"HC-blackout\">";
echo "</div>";

echo "<div id=\"HCPhotos-dups-blackout\" class=\"HC-blackout\">";
echo "<div class=\"HC-ref\"></div>";
echo "<div id=\"HCPhotos-dups\">
<h2>Duplicates</h2>
<div id=\"HCPhotos-dups-imgs\"></div>";
echo '<div class="HCPhotos-dups-buttons"><button onClick="HCUI.BlkOff(\'#HCPhotos-dups-blackout\');">Close</button><button onClick="HCPhotos.Duplicates.Merge();">Merge Selected</button></div>';
echo "</div>";
echo "</div>";

echo "</div>";
include(THEMEFOOT);