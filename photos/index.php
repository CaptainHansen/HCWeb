<?
require("{$_SERVER['DOCUMENT_ROOT']}/admin/auth.php");
use \HCWeb\DB;

$title="Photo Management";
$head="<link rel=\"stylesheet\" href=\"style.css\" />
<script type=\"text/javascript\" src=\"HCPhotos.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Cats.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Duplicates.js\"></script>
<script type=\"text/javascript\" src=\"HCPhotos.Upload.js\"></script>
<script type=\"text/javascript\" src=\"/js/EasyJaxFiles.js\"></script>
<script type=\"text/javascript\" src=\"/js/jquery.waitforimages.js\"></script>
<script type=\"text/javascript\" src=\"/js/HC.Progress.js\"></script>
<link rel=\"stylesheet\" href=\"/js/HCUI-defaults.css\" />";
//$beforelinks="";
$currentpage="More Tools...";
include("{$_SERVER['DOCUMENT_ROOT']}/header.php");
echo "<div class=\"surround\">";

echo "<div class=\"HCPhotos-buttons\">";

echo "<button onClick=\"HCPhotos.Delete();\" style=\"width: 180px;\">Delete Selected Photos</button>";
echo  "<button style=\"position: relative;\"><input type=\"file\" id=\"HCPhotos-upload\" multiple=\"multiple\">Upload Photos</button>";
echo "<button onClick=\"HCPhotos.Duplicates.Show();\">Show Duplicates</button>";


echo "<div class=\"center\" style=\"font-size: 14pt;\">Filter by Category <select id=\"filter\" onchange=\"HCPhotos.Filter()\">";
$filters = new Photos\Filters();
$filters -> GetOptions();
echo "</select></div>";

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
echo '<div class="HCPhotos-buttons"><button onClick="HCPhotos.Duplicates.Close();">Close</button><button onClick="HCPhotos.Duplicates.Merge();">Merge Selected</button></div>';
echo "</div>";
echo "</div>";

echo "</div>";
include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");