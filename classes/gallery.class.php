<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

class Gallery
{
    public $id;
    public $owner;
    public $description;
    private $photos;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newGallery){
        $database = new Database();
        $database->query('INSERT INTO galleries (owner, description) VALUES (:owner, :description)');
        $database->bind(':owner',$newGallery["owner"]);
        $database->bind(':description',$newGallery["description"]);

        try {
            $result = $database->execute();
            return Gallery::withID($database->lastInsertId());
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
        $this->database->query('SELECT * FROM galleries WHERE id = :id');
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
        try {
            $photos = [];
            foreach($this->getPhotos() as $photo) {
                array_push($photos, $photo->delete());
            }

            $this->database->query('DELETE from galleries WHERE id = :id');
            $this->database->bind(':id',$this->id);
            $galleryResult = $this->database->execute();
            return array("Gallery Deletion"=>$galleryResult,"Photos Deletion"=>$photos);
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    public function getPhotos(){
        if (isset($this->photos))
            return $this->photos;

        $this->database->query('SELECT id FROM photos WHERE gallery = :gallery');
        $this->database->bind(':gallery',$this->id);
        try {
            $rows = $this->database->resultset();
            $this->photos = [];
            if($this->database->rowCount() > 0) {
                foreach ($rows as $row) {
                    array_push($this->photos, Photo::withID($row['id']));
                }
            }
        }catch (PDOException $e) {
            //Error..
        }
        return $this->photos;
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->owner = $row["owner"];
        $this->description = $row["description"];
    }
}