<?php
class R66_Form_ChoiceType extends R66_Model {
  
  /**
   * @var R66_Form_Option array
   */
  protected $_options;
  
  public function __construct() {
    $this->_options = array();
    $this->_data = array (
      'label' => '',
      'container' => '', // The object group name such as "product"
      'attribute' => '', // The field name such as "name"
      'description' => '', // Optional description for form field
      'inline' => false, // Display choices in line or stacked,
      'required' => false,
      'valid' => true
    );
  }
  
  /**
   * Set the value of one of the keys in the private $_data array.
   * 
   * @param string $key The key in the $_data array
   * @param string $value The value to assign to the key
   * @return boolean
   */
  public function __set($key, $value) {
    $success = false;
    $valid_attributes = array_keys($this->_data);
    $valid_attributes[] = 'options';
    $valid_attributes[] = 'selected_option';
    $valid_attributes[] = 'selected_options';
    if(is_array($this->_data) && in_array($key, $valid_attributes)) {
      
      if($key == 'inline') {
        // Force 'inline' to be a boolean value
        $this->_data['inline'] = ($value === true) ? true : false;
      }
      elseif($key == 'options') {
        foreach($value as $option_value => $option_text) {
          $this->add_option($option_value, $option_text, false);
        }
      }
      elseif($key == 'selected_option') {
        $this->set_selected_by_value($value);
      }
      elseif($key == 'selected_options') {
        $this->deselect_all();
        foreach($value as $selected_option_value) {
          $this->set_selected_by_value($selected_option_value, true);
        }
      }
      else {
        $this->_data[$key] = $value;
        $success = true;
      }
      
    }
    return $success;
  }
  
  public function set_selected_by_value($value, $allow_multiselect=false) {
    if(is_array($this->_options)) {
      foreach($this->_options as $key => $option) {
        if($option->value == $value) {
          $this->_options[$key]->selected = true;
        }
        elseif(!$allow_multiselect) {
          $this->_options[$key]->selected = false;
        }
      }
    }
  }
  
  public function deselect_all() {
    if(is_array($this->_options)) {
      foreach($this->_options as $key => $option) {
        $this->_options[$key]->selected = false;
      }
    }
  }
  
  public function get_options() {
    if(!is_array($this->_options)) {
      $this->_options = array();
    }
    return $this->_options;
  }
  
  public function add_option() {
    $num_args = func_num_args();
    if($num_args == 1) {
      $option = func_get_arg(0);
      $this->add_option_by_object($option);
    }
    elseif($num_args >= 2) {
      $value = func_get_arg(0);
      $text = func_get_arg(1);
      $selected = ($num_args == 3) ? func_get_arg(2) : false;
      $this->add_option_by_values($value, $text, $selected);
    }
  }
  
  public function add_options_by_value_array($values) {
    foreach($values as $value) {
      $this->add_option_by_values($value, $value, false);
    }
  }
  
  public function add_opitons_by_key_value_array($values) {
    foreach($values as $value => $text) {
      $this->add_option_by_values($value, $text, false);
    }
  }
  
  protected function add_option_by_object(R66_Form_Option $option) {
    $this->_options[] = $option;
  }
  
  protected function add_option_by_values($value, $text, $selected) {
    $option = new R66_Form_Option();
    $selected = ($selected == true) ? true : false;
    $option->value = $value;
    $option->text = $text;
    $option->selected = $selected;
    $this->_options[] = $option;
  }
  
}
