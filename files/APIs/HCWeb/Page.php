<?
namespace HCWeb;

class Page {
	private static $getOnce = false;
	public static function Get($id){
		$ret = "<div id=\"HCWeb-Page-{$id}\" class=\"HCWeb-Page\">";
		if(Auth::isLoggedIn()) {
			if(!self::$getOnce) {
				Header::addCssJs("/js/HCUI-defaults.css");
				Header::addCssJs("/js/HCWeb.Page.js");
				Header::addCssJs('/ckeditor/ckeditor.js');
				Header::addCssJs('/ckeditor/adapters/jquery.js');
				self::$getOnce = true;
			}
			$ret .= "<div class=\"HCWeb-Page-buttons\"><button onclick=\"HCWeb.Page.Edit({$id});\">Edit</button></div>";
		}
		$ret .= "<div class=\"HCWeb-Page-contents\">";
		$r = DB::query("select html from pages where ID = {$id}");
		list($html) = $r -> fetch_row();
		$ret .= $html;
		$ret .= "</div></div>";
		return $ret;
	}
}