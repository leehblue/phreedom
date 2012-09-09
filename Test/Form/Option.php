<?php
require_once dirname(dirname(__FILE__)) . '/bootstrap.php';

class R66_Test_Form_Option extends R66_Test {
  
  public function test_setting_selected_should_allow_true_as_a_value() {
    $option = new R66_Form_Option();
    $option->selected = true;
    $this->check($option->selected, "Option should be able to be set to true: " . print_r($option->get_data(), true));
  }
  
  public function test_setting_selected_to_someting_other_than_true_should_store_as_false() {
    $option = new R66_Form_Option();
    $option->selected = true;
    $option->selected = 'banana';
    $this->check(!$option->selected, "Option should store non-true values as false: " . print_r($option->get_data(), true));
  }
  
  public function test_setting_option_value() {
    $option = new R66_Form_Option();
    $option->value = 'value';
    $this->check($option->value == 'value', "Option should store given values: " . print_r($option->get_data(), true));
  }
  
  public function test_setting_option_text() {
    $option = new R66_Form_Option();
    $option->text = 'text';
    $this->check($option->text == 'text', "Option should store given text: " . print_r($option->get_data(), true));
  }
  
}

R66_Test_Form_Option::run_tests();