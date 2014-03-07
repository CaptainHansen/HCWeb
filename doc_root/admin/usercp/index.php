<?
define("REQUIRE_ADMIN",true);
require('../auth.php');
use \HCWeb\DB;
use \HCWeb\Header;

Header::$title = "User Accounts";
Header::addCssJs(THEME_RELPATH.'/admin-common.css');
Header::addCssJs('HCUser.js');
Header::addCssJs('style.css');
Header::addCssJs('/js/date.js');
Header::addCssJs('/js/encryption.js');
Header::addCssJs('/js/HC.Crypt.js');
Header::setCurPage('adm_linkbar',"Edit Users");
require("{$_SERVER['DOCUMENT_ROOT']}/header.php");
echo "<div id=\"left-column-color\"></div>";
echo "<div class=\"column left\"><h1>Edit Users</h1><p>This page allows control over all user accounts associated with this site.  Rest assurred, you cannot de-authorize yourself if you are listed as an admin.</p></div>";

echo "<div class=\"blackout\" id=\"HCUser-blackout\">
<div class=\"ref\"></div><div class=\"HCUser-dialog\"></div>
</div>";

echo \HCWeb\EasyJax::getPubKey();
echo "<div id=\"main-text\">";

	echo "<table id=\"HCUser-table\">";
	echo "<tr><th>User</th><th>User Type</th><th>Last Activity</th><th>Full Name</th><th>IP Address</th></th></tr>";
/*
	while($u = $r -> fetch_assoc()) {
		if($u['admin'] == 1) {
			$adminb="De-Authorize";
		} else {
			$adminb="Authorize";
		}
		if($u['user'] == $_SESSION['user']) {
			$dis="disabled";
		} else {
			$dis="";
		}
		echo "<tr id=\"user-{$u['ID']}\"><td id=\"user\">{$u['user']}</td>";
		echo "<td><input $dis type=\"password\" id=\"pass\"></td><td><input $dis type=\"password\" id=\"verify\"></td>";
		echo "<td><button onclick='HCUser.ResetPass({$u['ID']})' $dis >Reset Password</button></td>";
		echo "<td><button $dis id=\"adminb\" onclick='HCUser.CHAdmin({$u['ID']})'>{$adminb}</button</td>";
		echo "<td><button onclick='HCUser.Delete({$u['ID']})' $dis >Delete User</button></td></tr>";
	}
*/
	echo "</table>";
?>
<div class="center"><button onclick="HCUser.New()">Create New User</button></div>
<?
echo "</div>";
require("{$_SERVER['DOCUMENT_ROOT']}/footer.php");