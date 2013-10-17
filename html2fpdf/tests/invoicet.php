<?
setlocale(LC_MONETARY,'en_US');
#include_once("/var/www/records/webroot/HC-Database/vars.php");

ob_start();

$list=array($_GET['rec']);
foreach($list as $id => $listitem) {
	include ( $dbfiles.$listitem );
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">";
echo "<html><head><title>Invoice ".date('Y-m-d Hi',$listitem)." - {$clientdata['clientname']}</title><link rel=stylesheet href=invstyle.css>";
echo "<center><div class=fieldid>Hansen Computers Appointment Invoice</div></center></head><br />";
	echo "<table class=invdata>";
echo "<tr><td id=$listitem class=fieldid>Client Name</td><td class=clientname>".$clientdata['clientname']."</td></tr>";
echo "<tr><td class=fieldid>Location</td><td class=residency>".$clientdata['residency']."</td></tr>";
$weeknum=(int)((($listitem - 1157342400) / 604800) + 1);
echo "<tr><td class=fieldid>Date of appointment</td><td class=datentime>".date('F j Y h:i A',$listitem)."</td></tr>";

echo "<tr><td class=fieldid>Problem with</td><td class=pcmacsterother>";
	if ($clientdata['pc'] === true) echo "PC ";
	if ($clientdata['mac'] === true) echo "Mac ";
	if ($clientdata['stereotv'] === true) echo "Stereo/TV ";
	if ($clientdata['other'] === true) echo "Other";
echo "</td></tr>";
echo "<tr><td class=fieldid>Hours</td><td><span class=hoursspent>".$clientdata['hoursspent']."</span></td></tr>";

if($clientdata['travel'] != 0) {
	echo "<tr><td class=fieldid>Total Travel Time</td><td class=hoursspent>".$clientdata['travel']." hour(s)</td></tr>";
}

echo "<tr><td class=fieldid>Rate</td><td><table><td style=\"vertical-align: top\" class=rate>".money_format('%n',$clientdata['rate'])." per hour<td align=right width=200><span style=\"font-size: 13pt\" class=fieldid>Amount Owed</span></td><td class=redalert>".money_format('%n',$clientdata['moneyearned'])."</td></tr></table></td></tr>";

echo "<td class=fieldid>Appointment summary</td><td class=summary>{$clientdata['summary']}</td></tr>";

echo "<td class=fieldid>Problem Status</td><td><span class=";
if ($clientdata['fixed'] == "Yes") {
	echo ">Problem Fixed";
} elseif ($clientdata['fixed'] == "See Below") {
	echo "alert>Partially Resolved";
} elseif ($clientdata['fixed'] == "No") {
	echo "redalert>Problem Not Fixed";
}
echo "</span></td></tr>";
if(isset($clientdata['ref']))
	if($clientdata['ref'] != "")
		echo "<tr><td class=fieldid>Referrer</td><td>{$clientdata['ref']}";

echo "<tr><td class=fieldid>Date Printed</td><td class=datentime>".date('F j Y h:i A')."</td></tr>";

include("/var/www/records/webroot/HC-Database/payto.html");
echo "</body></html>";
}

$htmlcode=ob_get_contents();
ob_end_clean();
echo $htmlcode;
?>
