<?php 
class R66_Form_Radio extends R66_Form_ChoiceType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/radio.phtml';
    $view = R66_View::get($path, array('radio' => $this));
    return $view;
  }
  
}


