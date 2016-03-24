<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

class Photo
{
    public $id;
    public $username;
    public $gallery;
    public $filename;
    public $description;
    public $uploaddate;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newPhoto){
        $database = new Database();
        $database->query('INSERT INTO photos (username, gallery, filename, description, uploaddate) VALUES (:username, :gallery, :filename, :description, :uploaddate)');
        $database->bind(':username',$newPhoto["username"]);
        $database->bind(':gallery',$newPhoto["gallery"]);
        $database->bind(':filename',$newPhoto["filename"]);
        $database->bind(':description',$newPhoto["description"]);
        $database->bind(':uploaddate',date("Y-m-d"));

        try {
            $result = $database->execute();
            return Photo::withID($database->lastInsertId());
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
        $this->database->query('SELECT * FROM photos WHERE id = :id');
        $this->database->bind(':id',$id);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
    }

    public function delete(){
        $this->database->query('DELETE from photos WHERE id = :id');
        $this->database->bind(':id',$this->id);

        try {
            $result = $this->database->execute();
            return true;
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->username = $row["username"];
        $this->gallery = $row["gallery"];
        $this->filename = $row["filename"];
        $this->description = $row["description"];
        $this->uploaddate = $row["uploaddate"];
    }
}