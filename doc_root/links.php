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

echo "<div class=\"linkbar-wrapper\">".$lbar -> getHTML()."</div>";
