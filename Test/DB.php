<?php
require_once 'bootstrap.php';

class Test_DB extends R66_Test {
  
  public function before_tests() {
    if(! R66_DB::run_file('Data/test_db.sql')) {
      echo "Unable to initialize test data.";
      $this->log_file_check();
      die();
    }
  }
  
  public function test_should_return_true_if_specified_table_exists() {
    $table_name = 'contacts';
    $table_exists = R66_DB::table_exists($table_name);
    $this->check($table_exists, "$table_name should exist in the database");
  }
  
  public function test_should_return_false_if_specified_table_is_not_found() {
    $table_name = 'missing_table_name';
    $table_exists = R66_DB::table_exists($table_name);
    $this->check(!$table_exists, "$table_name should not exist in the database");
  }
  
  public function test_should_return_true_if_specified_column_exists() {
    $table_name = 'contacts';
    $column = 'first_name';
    $exists = R66_DB::column_exists($table_name, $column);
    $this->check($exists, "${table_name}.${column} should exist :: " . print_r($exists, 1));
  }
  
  public function test_should_return_false_if_specified_column_is_not_found() {
    $table_name = 'contacts';
    $column = 'missing_column';
    $exists = R66_DB::column_exists($table_name, $column);
    $this->check(!$exists, "${table_name}.${column} should not exist");
  }
  
  public function _test_getting_database_name() {
    $name = R66_DB::get_database_name();
    echo "DATABASE: $name";
  }
  
}

Test_DB::run_tests();