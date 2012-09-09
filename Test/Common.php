<?php
require_once 'bootstrap.php';

class Test_Common extends R66_Test {
  
  public function test_rand_string_should_create_random_strings_of_specified_length() {
    $expected_length = 30;
    $string = R66_Common::rand_string($expected_length, true);
    $actual_length = strlen($string);
    echo $string;
    $this->check($expected_length == $actual_length, "Expected length $expected_length but got $actual_length");
  }
  
}

Test_Common::run_tests();