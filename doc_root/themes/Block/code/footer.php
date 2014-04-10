<?
echo "</div>";
$email = "someone@example.com";
echo "<div id=\"foot\">";

$social = new HCWeb\Social();
$social -> add("yelp");
$social -> add("facebook");
$social -> add("linkedin");
$social -> add("googleplus");
$social -> add("twitter");
$social -> add("instagram");
$social -> add("flickr");
$social -> add("youtube");
$social -> add("pinterest");
$social -> add("github","https://github.com/CaptainHansen");
$social -> add("tumblr");
echo $social;

echo "<span style=\"font-size: 12pt;\">Company Name</span><br />
<a style=\"text-decoration: none\" href=\"mailto:$email\">$email</a><hr style=\"width: 100px;\" />Send us an email!</div></div></body></html>";