<?
use \HCWeb\Header;

echo "</div>"; ////closing out main
$email = "someone@example.com";
echo "<div id=\"foot-wrapper\">
<div id=\"foot\"><div style=\"float: left; font-size: 12pt;\">Company Name</div>
<div style=\"float: right;\"><a style=\"text-decoration: none\" href=\"mailto:$email\">$email</a></div><div class=\"clear\"></div>
</div></div>";
Header::printJs();
echo "</body></html>";
