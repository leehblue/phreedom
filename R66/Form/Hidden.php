<?php 
class R66_Form_Hidden extends R66_Form_TextType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/hidden.phtml';
    $view = R66_View::get($path, array('hidden' => $this));
    return $view;
  }
  
}


