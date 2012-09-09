<?php 

class R66_DB {
  
  public static $last_query;
  
  private static $_db = false;
  
  public static function init() {
    if(!self::$_db) {
      global $db_credentials;
      self::$_db = new R66_Database($db_credentials);
    }
  }
  
  public static function set_database(R66_Database $db) {
    self::$_db = $db;
  }
  
  public static function get_database_name() {
    $sql = "SELECT DATABASE();";
    $result = self::get_value($sql);
    return $result;
  }
  
  /**
   * Run a SQL statement or an R66_Query and return a mysqli_result_set
   * 
   * @param mixed (string or R66_Query)
   * @return mysqli_result object
   */
  public static function query($sql) {
    self::init();
    self::$last_query = $sql;
    return self::$_db->query($sql);
  }
  
  /**
   * Return the first column of the first row of the result set.
   * If no result is available, return NULL
   */
  public static function get_value($sql) {
    self::init();
    $value = null;
    if($result = self::$_db->query($sql)) {
      if($row = $result->fetch_row()) {
        if(isset($row) && is_array($row) && isset($row[0])) {
          $value = $row[0];
        }
      }
    }
    else {
      throw new R66_Exception_Database_QueryFailed($sql);
    }
    return $value;
  }
  
  public static function last_query() {
    return self::$last_query;
  }
  
  public static function transaction($sql_queries) {
    self::init();
    return self::$_db->transaction($sql_queries);
  }
  
  public static function run_file($file_name) {
    $ok = false;
    if(file_exists($file_name)) {
      $sql = file_get_contents($file_name);
      $sql = explode(';', $sql);
      for($i=0; $i<count($sql); $i++) {
        $sql[$i] = trim($sql[$i]);
      }
      $ok = self::transaction($sql);
    }
    else {
      R66_Log::write("Unable to run sql because file does not exist: $file_name");
    }
    return $ok;
  }
  
  public static function escape($value) {
    self::init();
    return self::$_db->escape($value);
  }
  
  public static function last_id() {
    $id = false;
    if(self::$_db) {
      $id = self::$_db->last_id();
    }
    return $id;
  }
  
  public static function table_exists($table_name) {
    self::init();
    $db_details = self::$_db->get_connection_details();
    $sql = "select count(*)
      from information_schema.tables 
      where table_schema = '$db_details->database'
      and table_name = '$table_name'";
    $table_count = self::get_value($sql);
    return $table_count > 0;
  }
  
  public static function column_exists($table_name, $column) {
    $sql = "SHOW COLUMNS FROM `$table_name` LIKE '$column'";
    $result = self::query($sql);
    R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] column exists :: " . $result->num_rows);
    $is_found = $result->num_rows == 1;
    return $is_found;
  }
  
}