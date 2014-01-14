<?
use \HCWeb\Linkbar\Bar;
use \HCWeb\Linkbar\Link;

$lbar = new Bar();
$lbar -> addLink("Home","/");
$lbar -> addLink("Services","/services/");
$lbar -> addLink("Store","/store/",array(
	array("Paintings","/store/Paintings/"),
	array("Prints","/store/Prints/"),
	array("Services","/store/Services/"),
));
$lbar -> addLink("About","/about/");

echo "<div class=\"linkbar-wrapper\">".$lbar -> getHTML()."</div>";
