<?
namespace HTMLCollector;

class AccordionSingle extends Item {
	private $accordion;
	private $open_to_start;
	private $title;
	
	public function __construct($title,$menu_style='accordion_main',$width=0,$open_to_start=false){
		$this -> title = $title;
		$this -> open_to_start = $open_to_start;
		$this -> accordion = new \Accordion\Root(new \Accordion\Menu(),$menu_style,$width);
	}
	
	public function getHTML(){
		$this -> accordion -> addItem(new \Accordion\Items\HTML($this -> title, $this -> html, $this -> open_to_start));
		return $this->accordion->getHTML();
	}
}