<?
require("../auth.php");
use \HCWeb\Header;

Header::addCssJs(THEME_RELPATH."/admin-common.css");

Header::addCssJs("/js/EasyJaxFiles.js");
Header::addCssJs("/js/HC.Progress.js");
Header::addCssJs("upload.js");
Header::addCssJs("upload.css");
Header::addCssJs("/js/HCUI-defaults.css");
Header::setCurPage('adm_linkbar',"Upload Files");
include(THEMEHEAD);

echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Upload Files</h1><p>This page allows you to upload files of any size directly to the server.  To use this script, simply choose the files you would like to upload by clicking \"Upload Files\".  The upload process will start automatically.</p>";
echo "</div>";

?>
<div style="width: 0; height: 0; position: absolute;"><input type="file" id="files" multiple="multiple"></div>
<button id="btn">Upload Files</button>
<div id="results"></div>
<?

echo '<div id="allfiles">';

$files = scandir(FILESROOT."/uploaded/");
unset($files[0]);
unset($files[1]);
foreach($files as $file){
	echo "<div class=\"fname\">{$file}</div>";
}

echo "</div>";

include(THEMEFOOT);