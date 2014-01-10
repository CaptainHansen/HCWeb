<?
require("{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php");
use \HCWeb\Auth;

Auth::setLoginPage("/admin/login.php");
Auth::Init();