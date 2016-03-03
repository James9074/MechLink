<?php
include_once("includes/db_conn.php");

class User
{
    public $rlname;
    public $username;
    public $category;
    public $location;
    public $profile_id;
    public $gender;
    public $country;
    public $userlevel;
    public $avatar;
    public $signup;
    public $lastlogin;
    public $joindate;
    public $lastsession;
    public $description;
    public $activated;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function withUsername($username){
        $instance = new self();
        $instance->loadByUsername( $username );
        return $instance;
    }

    protected function loadByUsername( $username ) {
        $this->database->query('SELECT * FROM users WHERE username = :username');
        $this->database->bind(':username',$username);
        try {
            $row = $this->database->single();
            if($this->database->rowCount() > 0)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
    }

    protected function fill( array $row ) {
        $this->rlname = $row["rlname"];
        $this->username = $row["username"];
        $this->category = $row["category"];
        $this->location = $row["location"];
        $this->profile_id = $row["id"];
        $this->gender = $row["gender"] == "m" ? "male" : "female";
        $this->country = $row["country"];
        $this->userlevel = $row["userlevel"];
        $this->avatar = $row["avatar"];
        $this->signup = $row["signup"];
        $this->lastlogin = $row["lastlogin"];
        $this->joindate = $row["signup"];
        $this->lastsession = $row["lastlogin"];
        $this->description = $row["description"];
        $this->activated = $row["activated"] == 1 ? true : false;
    }
}