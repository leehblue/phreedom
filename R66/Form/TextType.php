<?php
class R66_Form_TextType extends R66_Model {

  public function __construct() {
    $this->_data = array (
      'label' => '',
      'container' => '',    // The object group name such as "product"
      'attribute' => '',    // The field name such as "name"
      'value' => '',        // The value of the text element
      'description' => '',  // Optional description for form field
      'required' => false,  // Whether or not the field is required
      'valid' => true,      // Whether or not the value is allowed
      'input_prefix' => '', // Text to appear immediately before the input field
      'input_suffix' => '', // Text to appear immediately after the input field
    );
  }

}
