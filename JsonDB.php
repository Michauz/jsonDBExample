<?php
class JsonDB {
    //The data stored in the JSON
    private $data;
     //The JSON
    private $jsonData;

    public function __construct($jsonData) {
        $this->jsonData = $jsonData;
        if (strlen($jsonData)>0) {
            $this->data = json_decode($jsonData);
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
    }

    // Select a specific row from the specified table
    public function select($table, $id) {
        return $this->data->$table[$id];
    }

    // Update a specific row in the specified table
    public function update($table, $id, $row) {
        $this->data->$table[$id] = $row;
    }

    // Delete a specific row from the specified table
    public function delete($table, $id) {
        unset($this->data->$table[$id]);
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
}
// you can create your own database or create an empty one
$data = array(
    'users' => array(
        array(
            'name' => 'Johny',
            'age' => 26,
            'email' => 'johny@example.com'
        ),
        array(
            'name' => 'Janey',
            'age' => 31,
            'email' => 'janey@example.com'
        )
    )
);

$jsonData = json_encode($data);
//$dbSelect = new JsonDBSelect($jsonData); // use a ready database
// Create a new instance of JsonDBSelect
$dbSelect = new JsonDBSelect('');

// Insert some data into the users table
$user1 = new stdClass();
$user1->name = 'John';
$user1->age = 25;
$user1->email = 'john@example.com';
$dbSelect->insert('users', $user1);

$user2 = new stdClass();
$user2->name = 'Jane';
$user2->age = 30;
$user2->email = 'jane@example.com';
$dbSelect->insert('users', $user2);

// Select a specific row from the users table
$user = $dbSelect->select('users', 0);
print_r($user);
$user = $dbSelect->select('users', 1);
print_r($user);
// Select specific columns from a row in the users table
$userColumns = $dbSelect->selectColumns('users', 0, array('name', 'age'));
print_r($userColumns);
?>
