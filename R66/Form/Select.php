<?php 
/**
 * Example usage:
 * 
 * $element = new R66_Form_Select();
 * $element->label = 'Example element';
 * $element->container = 'product';
 * $element->attribute = 'shipped';
 * $element->description = 'Is this product shipped?';
 * $element->inline = true;
 * $element->valid = false;
 * $element->add_option('1', 'Yes');
 * $element->add_option('0', 'No', true);
 * echo $element->get_html();
 */
class R66_Form_Select extends R66_Form_ChoiceType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/select.phtml';
    $view = R66_View::get($path, array('select' => $this));
    return $view;
  }
  
}


