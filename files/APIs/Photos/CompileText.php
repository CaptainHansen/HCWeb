<?
namespace Photos;

class CompileText {
	public static function Format($d){
		$ret = "<div class=\"message\">{$d['msg']}</div>";
		switch($d['pass']){
			case "1":
				$ret.= "<div class=\"pass\">Pass</div>";
			break;
			case "-1":
				$ret.= "<div class=\"fail\">COMPILE FAILURE</div>";
			break;
			case "-2":
				$ret.= "<div class=\"fail\">SOCKET FAILURE</div>";
			break;
			case "2":
				$ret.= "<div class=\"warn\">WARNING</div>";
			break;
			default:
				$ret.= "";
			break;
		}
		$ret.= "<div></div>";
		return $ret;
	}
}