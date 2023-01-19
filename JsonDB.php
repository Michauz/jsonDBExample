<?php
class JsonDB {
    //The data stored in the JSON
    private $data;
     //The JSON file
    private $jsonData;

    public function __construct($jsonData) {
        $this->jsonData = $jsonData;
        if (file_exists($jsonData)) {
            $this->data = json_decode(file_get_contents($jsonData));
        } else {
            $this->data = new stdClass();
        }
    }
    // Insert a new row in the specified table
    public function insert($table, $row) {
        if(!isset($this->data->$table)){
            $this->data->$table = array();
        }
        $this->data->$table[] = $row;
        $this->save();
    }

    // Select a specific row from the specified table
    public function select($table, $id) {
        return $this->data->$table[$id];
    }
    // Select all rows from the specified table
    public function selectAll($table) {
        return $this->data->$table;
    }
    // Update a specific row in the specified table
    public function update($table, $id, $row) {
        $this->data->$table[$id] = $row;
        $this->save();
    }

    // Delete a specific row from the specified table
    public function delete($table, $id) {
        unset($this->data->$table[$id]);
        $this->save();
    }
    private function save() {
      file_put_contents($this->jsonData, json_encode($this->data));
    }

}
class JsonDBSelect extends JsonDB {
    // Select specific columns from a row in the specified table
    public function selectColumns($table, $id, $columns) {
        
        $row = parent::select($table, $id);
        $selected = new stdClass();
        foreach ($columns as $column) {
          $selected->$column = $row->$column;
        }
        return $selected;
    }
    // Select all rows from the specified table, but only select specific columns from each row
    public function selectAllColumns($table, $columns) {
        $rows = parent::selectAll($table);
        $selectedRows = array();
        foreach ($rows as $row) {
          $selectedRows[] = $this->selectColumns($table, $row, $columns);
        }
        return $selectedRows;
    }
}
?>
