<?
require('../auth.php');
use \HCWeb\DB;

$title = "User Accounts";
$head = "<script type=\"text/javascript\" src=\"HCUser.js\"></script>
<link rel=\"stylesheet\" href=\"style.css\" />";
require("{$_SERVER['DOCUMENT_ROOT']}/header.php");

$r = DB::query("select * from auth");

	echo "<table id=\"users-table\">";
	echo "<tr><th>User</th><th>Reset Password</th><th>Verify Password</th></tr>";
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
	echo "</table>";
?>
<table id="user-new">
<tr><td>User Name</td><td><input type="text" id="user"></td></tr>
<tr><td>Password</td><td><input type="password" id="pass"></td></tr>
<tr><td>Verify</td><td><input type="password" id="verify"></td></tr>
<tr><td></td><td><button onclick="HCUser.Post()">Create New User</button></td></tr>
</table>

<?
require("{$_SERVER['DOCUMENT_ROOT']}/footer.php");