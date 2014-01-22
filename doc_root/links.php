<?
use \HCWeb\Linkbar\Bar;
use \HCWeb\Linkbar\Link;

$lbar = new Bar();
$lbar -> addLink(new Link("Home","/"));
$lbar -> addLink(new Link("Services","/services/"));
$lbar -> addLink(new Link("Store","/store/",array(
	array("Paintings","/store/Paintings/"),
	array("Prints","/store/Prints/"),
	array("Services","/store/Services/"),
)));
$lbar -> addLink(new Link("About","/about/"));

echo "<div class=\"linkbar-wrapper\">".$lbar -> getHTML($currentpage)."</div>";

use \HCWeb\Auth;
if(Auth::isLoggedIn()){
	$abar = new Bar();
	$abar -> addLink(new Link("Edit Pages","/admin/pages/"));
	$abar -> addLink(new Link("Manage Photos","/admin/photos/"));
	if(Auth::getData('admin') == 1){
		$abar -> addLink(new Link("Edit Users","/admin/usercp/"));
		$abar -> addLink(new Link("MySQL","/admin/mysql/"));
	}
	$abar -> addLink(new Link("Logout","/admin/logout.php"));
	
	$abar -> setNormalClass('adm_nav');
	$abar -> setActiveClass('adm_navc');
	$abar -> setBarClass('adm_linkbar');
	echo $abar -> getHTML($currentpage);
}