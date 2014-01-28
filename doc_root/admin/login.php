<?
require_once("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");
use \HCWeb\Auth;
use \HCWeb\Header;

Header::addCssJs("/js/encryption.js");
Header::addCssJs('/js/HCCrypt.js');

$easyj = new \HCWeb\EasyJax();

$data = $easyj -> getData();

switch($easyj -> req_method){
case "GET":
	if(Auth::Init(false)){
		header("Location: /");
		die;
	}
	$no_analytics = true;
	//$beforelinks=
	//$currentpage=
	$disable_mobile = true;
	include("{$_SERVER['DOCUMENT_ROOT']}/header.php");
	echo "\n<script>
<!--
function login(){
	easyj = new EasyJax(window.location.href,'LOGIN',function(){
		$('#loginfrm').submit();
		window.location.href = '/';
	},{'user':$('#user').val(), 'pass':$('#pass').val()});
	easyj.submit_data();
}

var easyj;
$(document).ready(function(){
	$('#user, #pass').keydown(function(event){
		if(event.keyCode == 13){
			login();
		}
	});
});
-->
</script>\n";
echo \HCWeb\EasyJax::getPubKey();
	echo '<div id="main-text">
	<div style="text-align: center; font-weight: bold;">Admin Login</div>
	<iframe id="dumb" name="dumb" style="display: none;"></iframe>
	<form method="post" id="loginfrm" action="blank.html" target="dumb"><table style="margin-left: auto; margin-right: auto;">
	<tr><td>Username</td><td><input id="user" name="user" type="text"></td>
	</tr><tr>
	<td>Password</td><td><input id="pass" name="pass" type="password"></td>
	</tr></table></form>
	<div style="text-align: center;"><button class="small" onclick="login();">Login</button></div>
	</div>';
	include("{$_SERVER['DOCUMENT_ROOT']}/footer.php");
	die;

case "LOGIN":
	if(!$easyj -> isSecure()) {
		$easyj -> add_error_msg("Login page not secure.  Login attempt rejected.");
		break;
	}
	if(!isset($data['user']) || !isset($data['pass'])){
		$easyj -> add_error_msg("Username and Password were not submitted.");
		break;
	}
	if(!Auth::Login($data['user'],$data['pass'])){
		$easyj -> add_error_msg("Either your credentials are invalid or you are not authorized to login to make changes here.");
		break;
	}
	break;

default:
	$easyj -> add_error_msg("Request method not recognized.");

}
$easyj -> send_resp();
