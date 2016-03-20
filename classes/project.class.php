<?php
include_once("includes/db_conn.php");

class Project
{
    public $id;
    public $automobiletype;
    public $location;
    public $details;
    public $skills;
    public $username;
    public $dateposted;
    public $gallery;
    public $photos;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newProject){
        $database = new Database();
        $database->query('INSERT INTO projects (automobiletype, location, details, skills, username, dateposted) VALUES (:automobiletype, :location, :details, :skills, :username, :dateposted)');
        $database->bind(':automobiletype',$newProject["automobiletype"]);
        $database->bind(':location',$newProject["location"]);
        $database->bind(':details',$newProject["details"]);
        $database->bind(':skills',$newProject["skills"]);
        $database->bind(':username',$newProject["username"]);
        $database->bind(':dateposted',date("Y-m-d"));

        try {
            $result = $database->execute();
            return Project::withID($database->lastInsertId());
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    public static function withID($id){
        $instance = new self();
        $instance->loadByID( $id );
        return $instance;
    }

    protected function loadByID( $id ) {
        $this->database->query('SELECT * FROM projects WHERE id = :id');
        $this->database->bind(':id',$id);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
    }

    public function getPhotos(){
        if (isset($this->photos))
            return $this->photos;

        if($this->gallery == null)
            return [];

        $this->database->query('SELECT id FROM photos WHERE gallery = :gallery');
        $this->database->bind(':gallery',$this->gallery);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0) {
                $this->photos = [];
                foreach ($rows as $row) {
                    array_push($this->photos, Photo::withID($row['id']));
                }
            }
            else
                $this->photos = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->photos;
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->automobiletype = $row["automobiletype"];
        $this->location = $row["location"];
        $this->details = $row["details"];
        $this->skills = $row["skills"];
        $this->username = $row["username"];
        $this->dateposted = $row["dateposted"];
        $this->gallery = $row["gallery"];
    }
}