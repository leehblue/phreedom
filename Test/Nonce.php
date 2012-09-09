<?php
require_once 'bootstrap.php';

class Test_Nonce extends R66_Test {
  
  public function test_blending_two_strings_where_a_is_longer_than_b() {
    $a = 'abcdefg';
    $b = '12345';
    $expected = 'a1b2c3d4e5fg';
    $actual = R66_Nonce::blend($a, $b);
    $this->check($expected == $actual, "Expected $expected got $actual");
  }
  
  public function test_blending_two_strings_where_b_is_longer_than_a() {
    $a = 'abcdefg';
    $b = '123456789';
    $expected = 'a1b2c3d4e5f6g789';
    $actual = R66_Nonce::blend($a, $b);
    $this->check($expected == $actual, "Expected $expected got $actual");
  }
  
  public function test_blending_a_string_and_a_number() {
    $a = 'abcdefg';
    $b = 12345;
    $expected = 'a1b2c3d4e5fg';
    $actual = R66_Nonce::blend($a, $b);
    $this->check($expected == $actual, "Expected $expected got $actual");
  }
  
  public function test_unblend_string() {
    $blended_string = 'a1b2c3d4efg';
    $known_string = 'abcdefg';
    $expected = '1234';
    $actual = R66_Nonce::unblend($blended_string, $known_string);
    $this->check($expected == $actual, "Expected $expected got $actual");
  }
  
  public function test_validating_a_nonce() {
    $token = 'ABCDEFG';
    $nonce = R66_Nonce::generate('delete_user', $token);
    $is_valid = R66_Nonce::validate($nonce, 'delete_user', $token);
    $this->check($is_valid);
  }
  
  public function test_validating_an_invalid_nonce_should_throw_an_exception() {
    $passed = false;
    $token = 'ABCDEFG';
    $nonce = R66_Nonce::generate('delete_user', $token);
    try {
      R66_Nonce::validate($nonce, 'different_action', $token);
    }
    catch(R66_Exception_Nonce $e) {
      $message = $e->getMessage();
      if($message == 'invalid nonce') {
        $passed = true;
      }
    }
    $this->check($passed, 'Did not catch expected R66_Exception_Nonce');
  }
  
  public function test_validating_an_expired_nonce_should_throw_an_exception() {
    $passed = false;
    $token = 'ABCDEFG';
    $nonce = R66_Nonce::generate('delete_user', $token);
    try {
      sleep(2);
      R66_Nonce::validate($nonce, 'delete_user', $token, 1);
    }
    catch(R66_Exception_Nonce $e) {
      $message = $e->getMessage();
      if($message == 'expired nonce') {
        $passed = true;
      }
    }
    $this->check($passed, 'Did not catch expected R66_Exception_Nonce');
  }
  
}

Test_Nonce::run_tests();