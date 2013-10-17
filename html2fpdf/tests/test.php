<?
/*
require_once("{$_SERVER['DOCUMENT_ROOT']}/auth.php");
if(!isset($_REQUEST['db']) || !isset($_REQUEST['rec'])){
	header('Location: /');
	die;
}

if(!is_dir($_REQUEST['db'])){
	header('Location: /');
	die;
}

chdir($_REQUEST['db']);
define('RELATIVE_PATH','/var/www/records/html2fpdf/');
require(RELATIVE_PATH."html2fpdf.php");

#require("invoicet.php");
*/
#$_GET['rec']="001205783100";
require("../html2fpdf.php");
$htmlcode=file_get_contents("test4.html");

$pdf=new HTML2FPDF();
$pdf->AddPage();
$pdf->WriteHTML($htmlcode);
$pdf->Output("test2.pdf","F");
?>
