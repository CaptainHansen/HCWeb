<?
require("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");
\HCWeb\Auth::Logout();
header("Location: /admin/login.php");