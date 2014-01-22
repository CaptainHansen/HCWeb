<?
require("{$_SERVER['DOCUMENT_ROOT']}/admin/auth.php");
use \HCWeb\DB;

$title="Photo Management";
$head="<link rel=\"stylesheet\" href=\"/admin/common.css\" />
<link rel=\"stylesheet\" href=\"style.css\" />
<script type=\"text/javascript\" src=\"/js/HC.Progress.js\"></script>
<script type=\"text/javascript\" src=\"/js/HC.Slider.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Cats.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Duplicates.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Upload.js\"></script>
<script type=\"text/javascript\" src=\"/js/EasyJaxFiles.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery.waitforimages.js\"></script>
<link rel=\"stylesheet\" href=\"/js/HCUI-defaults.css\" />";
//$beforelinks="";
$currentpage="Photo Manager";
include("{$_SERVER['DOCUMENT_ROOT']}/header.php");

echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Photo Manager</h1><p>This page shows all photos uploaded to this site and allows control over what photos show up on the Home page of the site.  Duplicates can be easily resolved, photos can be removed (deleting a photo here also deletes it from the portfolio section of the website), and multiple photos can be uploaded at the same time.</p></div>";

echo "<div id=\"main-text\">";

echo "<div class=\"HCPhotos-buttons\">";

echo "<button onClick=\"HCPhotos.Delete();\" style=\"width: 180px;\">Delete Selected Photos</button>";
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

echo "<div id=\"HCPhotos-upload-blackout\" class=\"HCPhotos-blackout\">";
echo "</div>";

echo "<div id=\"HCPhotos-dups-blackout\" class=\"HCPhotos-blackout\">";
echo "<div class=\"HCPhotos-ref\"></div>";
echo "<div id=\"HCPhotos-dups\">
<div class=\"title\">Duplicates</div>
<div id=\"HCPhotos-dups-imgs\"></div>";
echo '<div class="HCPhotos-dups-buttons"><button onClick="HCPhotos.Duplicates.Close();">Close</button><button onClick="HCPhotos.Duplicates.Merge();">Merge Selected</button></div>';
echo "</div>";
echo "</div>";

echo "</div>";
include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");