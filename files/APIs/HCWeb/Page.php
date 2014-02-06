<?
namespace HCWeb;

class Page {
	public static function Get($id){
		$r = DB::query("select html from pages where ID = {$id}");
		list($html) = $r -> fetch_row();
		return $html;
	}
}