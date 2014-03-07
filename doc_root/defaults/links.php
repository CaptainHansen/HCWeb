<?
use \HCWeb\Linkbar\Bar;
use \HCWeb\Linkbar\Link;
use \HCWeb\Auth;

$lbar = new Bar();
$lbar -> setBarID('linkbar');
$lbar -> add(new Link("Home","/"));
$lbar -> add(new Link("Services","/services/"));
$lbar -> add(new Link("Store","/store/",array(
	array("Paintings","/store/Paintings/"),
	array("Prints","/store/Prints/"),
	array("Services","/store/Services/"),
)));
$lbar -> add(new Link("About","/about/"));

echo "<div class=\"linkbar-wrapper\">".$lbar -> getHTML();

if(Auth::isLoggedIn()){
	$abar = new Bar();
	$abar -> add(new Link("Edit Pages","/admin/pages/"));
	$abar -> add(new Link("Manage Photos","/admin/photos/"));
	$abar -> add(new Link("Upload Files","/admin/upload-files/"));
	if(Auth::getData('admin') == 1){
		$abar -> add(new Link("Edit Users","/admin/usercp/"));
		$abar -> add(new Link("MySQL","/admin/mysql/"));
	}
	$abar -> add(new Link("Logout","/admin/logout.php"));
	
	$abar -> setNormalClass('adm_nav');
	$abar -> setActiveClass('adm_navc');
	$abar -> setBarClass('adm_linkbar');
	$abar -> setBarID('adm_linkbar');
	echo $abar -> getHTML();
}

echo "</div>";