<?php 
class R66_Form_Textarea extends R66_Form_TextType {
  
  public function get_html() {
    $path = dirname(__FILE__) . '/views/textarea.phtml';
    $view = R66_View::get($path, array('textarea' => $this));
    return $view;
  }
  
}


