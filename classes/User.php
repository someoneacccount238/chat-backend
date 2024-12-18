
<?php
class User
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;

    public function __construct() {}

    public function getAllUsers()
    {
        // create a database connection, using the constants from config/db.php (which we loaded in index.php)
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // check if user or email address already exists
        $sql = "SELECT user_id, user_name, avatar, user_email FROM users;";
        $dataArr =  $this->db_connection->query($sql);

        /* associative array */

        //for($i = 0; $i < count($dataArr); $i++){
        // array_push($item[$i], $arr);
        $users = array();

        while (($row = $dataArr->fetch_array(MYSQLI_ASSOC))) {
            array_push($users,$row);
        }

        //print_r($users);


        //print_r($dataArr);
        return $users;
    }
}
