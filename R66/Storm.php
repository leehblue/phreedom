<?php 

class R66_Storm extends R66_Model {
  
  protected $_table;
  protected $_data;
  protected $_data_types;
  protected $_errors;
  protected $_required;
  
  /**
   * Create a new Storm object.
   * 
   * The parameters are passed to the tie() function so that
   * classes can extend and override the constructor using the same
   * function signature yet still allowing the extended class to 
   * use a different number of variables.
   * 
   * @param string $table The database table name
   * @param int $id (Optional) The primary key of the table row to load
   */
  public function __construct() {
    call_user_func_array(array($this,'tie'), func_get_args());
  }
  
  /**
   * Tie the model to an underlying database table.  
   * 
   * An id may optionally be provided to tie the model to a specific row in the table.
   * 
   * @param string $table The database table name
   * @param int $id (Optional) The primary key of the table row to load
   * @return void
   */
  public function tie($table, $id=null) {
    $this->_table = $table;
    $this->_init();
    
    // Tie the model to a specific row if an id is provided
    if(isset($id) && is_numeric($id) && $id > 0) {
      $this->load($id);
    }
  }
  
  /**
   * Load the model with a row from the table with the given id (primary key)
   * If the load succeeds return true, otherwise false.
   * 
   * @param int $id The id in the primary key column of the table
   * @return boolean
   */
  public function load($id) {
    $loaded = false;
    if(is_numeric($id) && $id > 0) {
      $query = new R66_Query();
      $query->select($this->_table)->where('id', '=', $id);
      if($result = R66_DB::query($query)) {
        if($row = $result->fetch_assoc()) {
          $this->copy_from($row);
          $loaded = true;
        }
      }
    }
    
    if($loaded && method_exists($this, 'after_load')) {
      $this->after_load($this->id);
    }
    
    return $loaded;
  }
  
  public function load_where() {
    $loaded = false;
    $condition = null;
    $order_by = null;
    $num_args = func_num_args();
    if($num_args == 1) {
      $condition = func_get_arg(0);
    }
    elseif($num_args == 2) {
      $condition = func_get_arg(0);
      $order_by = func_get_arg(1);
    }
    elseif($num_args == 3) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
    }
    elseif($num_args == 4) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
      $order_by = func_get_arg(3);
    }
    
    if($obj = $this->find_one($condition, $order_by)) {
      $this->copy_from($obj->get_data());
      $loaded = true;
    }
    
    if($loaded && method_exists($this, 'after_load')) {
      $this->after_load($this->id);
    }
    
    return $loaded;
  }
  
  /**
   * Just like the Model copyFrom except it only copies scalar values
   */
  public function copy_from(array $data) {
    foreach($data as $key => $value) {
      if(is_scalar($value) && $this->field_exists($key)) {
        $this->$key = $value;
      }
    }
  }
  
  /**
   * Clear all the values in the model and delete the associated row in the table.
   * 
   * Return true if the storm is successfully erased, otherwise false
   * 
   * @return boolean
   */
  public function erase() {
    $ok = false;
    if($this->id > 0) {
      $sql = 'delete from ' . $this->_table . ' where `id` = ' . $this->id;
      R66_DB::query($sql);
      $this->clear();
      $ok = true;
    }
    return $ok;
  }
  
  public function find_all($order_by=null) {
    return $this->find_where('id', '>', 0, $order_by);
  }
  
  /**
   * Return an array of objects matching the where criteria.
   * 
   * If no objects are found, an empty array is returned.
   * 
   * @return array
   */
  public function find_where() {
    $objects = array();
    $condition = false;
    $order_by = false;
    $num_args = func_num_args();
    if($num_args == 1) {
      $condition = func_get_arg(0);
    }
    elseif($num_args == 3) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
    }
    elseif($num_args == 4) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
      $order_by = func_get_arg(3);
    }
    
    if($condition) {
      $query = new R66_Query();
      $query->select($this->_table)
            ->where($condition)
            ->order($order_by);
      if($result = R66_DB::query($query)) {
        $my_class = get_class($this);
        while($row = $result->fetch_assoc()) {
          $obj = new $my_class($this->_table);
          $obj->copy_from($row);
          $objects[] = $obj;
        }
      }
    }
    
    return $objects;
  }
  
  public function find_one() {
    $object = false;
    $condition = false;
    $order_by = false;
    $num_args = func_num_args();
    if($num_args == 1) {
      $arg = func_get_arg(0);
      if(is_array($arg) || get_class($arg) == 'R66_QueryCondition') {
        $condition = $arg;
      }
    }
    elseif($num_args == 2) {
      $condition = func_get_arg(0);
      $order_by = func_get_arg(1);
    }
    elseif($num_args == 3) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
    }
    elseif($num_args == 4) {
      $condition = R66_QueryCondition::factory(func_get_arg(0), func_get_arg(1), func_get_arg(2));
      $order_by = func_get_arg(3);
    }
    
    if($condition) {
      $query = new R66_Query();
      $query->select($this->_table)
            ->where($condition)
            ->order($order_by)
            ->limit(1);
      $sql = $query->get_sql();
      if($result = R66_DB::query($query)) {
        $my_class = get_class($this);
        if($row = $result->fetch_assoc()) {
          $object = new $my_class($this->_table);
          $object->copy_from($row);
        }
      }
    }
    return $object;
  }
  
  /**
   * Return the id of the object that was saved.
   * 
   * If the save fails, return false.
   * 
   * @return int or false
   */
  public function save() {
    if(func_num_args() == 1) {
      $data = func_get_arg(0);
      if(is_array($data)) {
        $this->clear();
        $this->copy_from($data);
      }
    }
    
    // Run the before_save hook
    if(method_exists($this, 'before_save')) {
      $this->before_save();
    }
    
    // validate before saving
    if($this->validate()) {
      return $this->id > 0 ? $this->_update() : $this->_insert();
    }
    else {
      $e = new R66_Exception_Validation("Unable to save product because validation failed");
      $e->set_errors($this->get_errors());
      throw $e;
    }
    
  }
  
  /**
   * Return the internal data array which represents the database table row
   * 
   * @return array
   */
  public function get_data() {
    return $this->_data;
  }
  
  /**
   * Return true if all required fields are present.
   * 
   * All errors are added to the $_errors array
   * 
   * @return boolean
   */
  public function validate() {
    $this->_errors = array(); // Clear previous errors before validating
    if(is_array($this->_required)) {
      foreach($this->_required as $key) {
        if(empty($this->$key)) {
          $this->_errors[$key] = "$key is required";
        }
      }
    }
    $is_valid = count($this->_errors) == 0;
    return $is_valid;
  }
  
  /**
   * Return the internal array of errors
   * 
   * @return array
   */
  public function get_errors() {
    return $this->_errors;
  }
  
  /**
   * Set the internal errors array to the given array
   */
  public function set_errors(array $errors) {
    $this->_errors = $errors;
  }
  
  /**
   * Add an error to the errors array
   * 
   * If one parameter is provided it is the error message
   * If two parameters are provided the first is the key and the second is the message
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
    else {
      throw new R66_Exception_InvalidFunctionCall("R66_Storm->add_error() was called with an invalid number of parameters. Parameter count: $num_args");
    }
  }
  
  /**
   * Return true if the specified column name contains a value that is not
   * present in any row of the database with the possible exception of its own row.
   * 
   * @return boolean
   */
  public function attribute_unique($column_name) {
    $id = $this->id;
    if(!isset($id) || $id < 1) {
      $id = 0;
    }
    $sql = 'SELECT count(*) FROM ' . $this->_table . " where `$column_name`='" . $this->$column_name . "' and id != " . $id;
    $result = R66_DB::query($sql);
    $row = $result->fetch_row();
    return $row[0] == 0;
  }
  
  public function __call($name, $args) {
    if(R66_Validators::starts_with($name, 'html_for_')) {
      $column_name = substr($name, 9);
      $params = array();
      if(count($args) == 1 && is_array($args[0])) {
        $params = $args[0];
      }
      return $this->generate_markup($column_name, $params);
    }
    elseif(R66_Validators::starts_with($name, 'load_by_')) {
      $is_loaded = false;
      if(count($args) == 1) {
        $column_name = substr($name, 8);
        $value = $args[0];
        $is_loaded = $this->load_where($column_name, '=', $value);
      }
      return $is_loaded;
    }
  }
  
  public function generate_markup($column_name, $params=array('type' => 'text')) {
    $html = '';
    
    if($this->field_exists($column_name)) {
      $defaults = array(
        'container' => get_class($this),
        'label' => ucfirst(str_replace('_', ' ', $column_name)),
        'attribute' => $column_name,
        'description' => '',
        'value' => $this->$column_name,
        'required' => false,
        'valid' => true
      );
      
      $type = 'text';
      if(array_key_exists('type', $params)) {
        $type = $params['type'];
        unset($params['type']);
      }
      
      $markup_settings = array_merge($defaults, $params);
      $element_type = 'R66_Form_' . ucfirst(strtolower($type));
      
      if(class_exists($element_type)) {
        $element = new $element_type;
        foreach($markup_settings as $key => $value) {
          $element->$key = $value;
        }
        
        if(is_a($element, 'R66_Form_TextType')) {
          $element->value = $this->$column_name;
        }
        elseif(is_a($element, 'R66_Form_ChoiceType')) {
          if(strlen($this->$column_name) >= 1) {
            $element->set_selected_by_value($this->$column_name);
          }
        }
        
        // Check for error on this attribute
        if(is_array($this->_errors) && array_key_exists($column_name, $this->_errors)) {
          $element->valid = false;
        }
        
        $html = $element->get_html();
      }
      
    }
    
    return $html;
  }
  
  // ===================================
  // = Private and Protected Functions =
  // ===================================
  
  protected function _init() {
    $this->_data = array();
    $this->_data_types = array();
    
    $sql = 'show columns from ' . $this->_table;
    $result = R66_DB::query($sql);
    if($result) {
      while($col_meta = $result->fetch_assoc()) {
        $default = ($col_meta['Key'] == 'PRI') ? null : '';
        $this->_data[$col_meta['Field']] = $default;

        $matches = array();
        $pattern = '/([^\(]+)(\(([^\(]+)\))*/';
        preg_match($pattern, $col_meta['Type'], $matches);

        $info = new stdClass();
        $info->type = isset($matches[1]) ? $matches[1] : false;
        $info->length = (isset($matches[3]) && is_numeric($matches[3]) ) ? $matches[3] : 0;

        if($info->type == "enum") {
          $opts = array();
          foreach(explode(',', $matches[3]) as $val) {
            $opts[] = trim($val, "'");
          }
          $info->options = $opts;
        }

        $this->_data_types[$col_meta['Field']] = $info;
      }
    }
  }
  
  protected function _insert() {
    $id = false;
    $query = new R66_Query();
    $data = $this->get_data();
    $query->insert($this->_table, $data);
    if(R66_DB::query($query)) {
      $this->id = R66_DB::last_id();
      $id = $this->id;
    }
    return $id;
  }
  
  protected function _update() {
    $id = false;
    $query = new R66_Query();
    $data = $this->get_data();
    $query->update($this->_table, $data);
    if(R66_DB::query($query)) {
      $id = $this->id;
    }
    return $id;
  }
  
}