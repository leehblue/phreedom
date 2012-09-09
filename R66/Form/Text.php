<?php 
class R66_Form_Text extends R66_Form_TextType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/text.phtml';
    $view = R66_View::get($path, array('text' => $this));
    return $view;
  }
  
}


