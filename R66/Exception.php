<?php
/**
 * Extend the base exception class to include an array of error messages
 */
class R66_Exception extends Exception {
  
  /**
   * An array holding error messages.
   * 
   * An example of the errors array looks like this:
   * 
   * $e = new R66_Exception();
   * $e->add_error('Unable to save object to database')
   * $e->add_error('first_name', 'First name is required')
   * $errors = $e->get_errors();
   * print_r($errors);
   * 
   * array (
   *   0 => 'Unable to save object to database',
   *   'first_name' => 'First name is required'
   * )
   * 
   * @var array
   */
  protected $_errors = array();
  
  /**
   * Add an error to the array of error messages.
   * 
   * If one parameter is provided it is assumed to me the error message and
   * the key will be auto-assigned.
   * 
   * Ir two parameters are provided the first is the key and the second is
   * the error message. Adding an error message with the same key as an
   * existing error message will replace the old message with the new message.
   */
  public function add_error() {
    $num_args = func_num_args();
    if($num_args == 1) {
      $message = func_get_arg(0);
      $this->_errors[] = $message;
    }
    elseif($num_args == 2) {
      $key = func_get_arg(0);
      $message = func_get_arg(1);
      $this->_errors[$key] = $message;
    }
  }
  
  /**
   * Set the $errors arrary to the give array replacing all previous values
   */
  public function set_errors(array $errors) {
    $this->_errors = $errors;
  }
  
  /**
   * Return the $errors array
   * 
   * @return array
   */
  public function get_errors() {
    return $this->_errors;
  }
}