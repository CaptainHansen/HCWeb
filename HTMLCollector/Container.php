<?
namespace HTMLCollector;

/** Container to interface with an HTMLCollector\Item
 *
 * Provides a wrapping structure for one HTMLCollector\Item
 *
 * Expects 2 CSS classes to be defined:
 * <ul>
 * <li>{class_prefix}-container (for the entire block)</li>
 * <li>{class_prefix}-title (for the title of the block)</li>
 * </ul>
 * Also provides the option to give the entire item its own DOM id
 */
class Container extends Item {
	/** The title of the Item */
	protected $title;
	
	/** The prefix of the CSS classes used to style this container */
	protected $class_prefix = 'main';
	
	/** ID to be assigned to the whole container (optional) */
	protected $id;
	
	/** __construct()
	* @param string [$title] The title of the Item being created
	* @param string [$class_prefix] The prefix to the class names for the container and the title of the container
	* @param string [$id] The id to be assigned to the container (optional)
	* @return Container new item
	*/
	public function __construct($title,$class_prefix='main',$id=""){
		$this -> title = $title;
		$this -> class_prefix = $class_prefix;
		$this -> id = $id;
	}
	
	/** getHTML()
	 * @return string The HTML code for the container
	 */
	public function getHTML(){
		$html = "<div class=\"{$this->class_prefix}-container\"";
		if($this -> id != "") $html .= " id=\"{$this->id}\"";
		$html .= "><div class=\"{$this->class_prefix}-title\">{$this->title}</div>";
		$html .= $this -> html;
		$html .= "</div>";
		return $html;
	}
}