<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

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
    private $galleryObject;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newProject){
        $database = new Database();
        $database->query('INSERT INTO projects (automobiletype, location, details, skills, username, gallery, dateposted) VALUES (:automobiletype, :location, :details, :skills, :username, :gallery, :dateposted)');
        $database->bind(':automobiletype',$newProject["automobiletype"]);
        $database->bind(':location',$newProject["location"]);
        $database->bind(':details',$newProject["details"]);
        $database->bind(':skills',$newProject["skills"]);
        $database->bind(':username',$newProject["username"]);
        $database->bind(':gallery',$newProject["gallery"]);
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

    public function update($updateData){
        $this->database->query('UPDATE projects SET automobiletype = :automobiletype, location = :location, details = :details, skills = :skills, gallery = :gallery WHERE id = :id');
        $this->database->bind(':id', $this->id);
        $this->database->bind(':automobiletype',isset($updateData["automobiletype"]) ? $updateData["automobiletype"] : $this->automobiletype);
        $this->database->bind(':location',isset($updateData["location"]) ? $updateData["location"] : $this->location);
        $this->database->bind(':details',isset($updateData["details"]) ? $updateData["details"] : $this->details);
        $this->database->bind(':skills',isset($updateData["skills"]) ? $updateData["skills"] : $this->skills);
        $this->database->bind(':gallery',isset($updateData["gallery"]) ? $updateData["gallery"] : $this->gallery);

        try {
            $result = $this->database->execute();
            $this->loadByID($this->id);
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    public function delete(){
        $this->database->query('DELETE from projects WHERE id = :id');
        $this->database->bind(':id',$this->id);

        try {
            $galleryDelete = $this->getGallery()->delete();

            $projectDelete = "";
            if($galleryDelete == true){
                $projectDelete = $this->database->execute();
            }
            return array("Project Deletion"=>$projectDelete, "Gallery Deletion"=>$galleryDelete, "Gallery"=>$this->getGallery());
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
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

    public function getGallery(){
        if (isset($this->galleryObject))
            return $this->galleryObject;
        try {
            $this->galleryObject = Gallery::withID($this->gallery);

        }catch (PDOException $e) {
            //Error..
        }
        return $this->galleryObject;
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