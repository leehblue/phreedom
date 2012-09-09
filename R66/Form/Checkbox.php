<?php 
class R66_Form_Checkbox extends R66_Form_ChoiceType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/checkbox.phtml';
    $view = R66_View::get($path, array('checkbox' => $this));
    return $view;
  }
  
}


