<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

class School
{
    public $id;
    public $name;
    public $location;
    public $attendedfrom;
    public $attendedto;
    public $awards;
    public $degree1;
    public $degree2;
    public $degree3;
    public $degree4;
    public $skillset;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newSchool){

        $database = new Database();
        $database->query('INSERT INTO schools (name, location, attendedfrom, attendedto, awards, degree1, degree2, degree3, degree4, skillset) VALUES (:name, :location, :attendedfrom, :attendedto, :awards, :degree1, :degree2, :degree3, :degree4, :skillset)');
        $database->bind(':name',$newSchool["name"]);
        $database->bind(':location',$newSchool["location"]);
        $database->bind(':attendedfrom',$newSchool["attendedfrom"]);
        $database->bind(':attendedto',$newSchool["attendedto"]);
        $database->bind(':awards',$newSchool['awards']);
        $database->bind(':degree1',$newSchool['degree1']);
        $database->bind(':degree2',$newSchool['degree2']);
        $database->bind(':degree3',$newSchool['degree3']);
        $database->bind(':degree4',$newSchool['degree4']);
        $database->bind(':skillset',$newSchool["skillset"]);

        try {
            $result = $database->execute();
            return School::withID($database->lastInsertId());
        }catch (PDOException $e) {
            //Error...
            print($e);
            return $e;
        }
    }

    public static function withID($id){
        $instance = new self();
        $instance->loadByID( $id );
        return $instance;
    }

    public function update($updateData){
        $this->database->query('UPDATE schools SET name = :name, location = :location, attendedfrom = :attendedfrom, attendedto = :attendedto, awards = :awards, degree1 = :degree1, degree2 = :degree2, degree3 = :degree3, degree4 = :degree4, WHERE id = :id');
        $this->database->bind(':id',$this->id);
        $this->database->bind(':name',$updateData["name"]);
        $this->database->bind(':location',$updateData["location"]);
        $this->database->bind(':attendedfrom',$updateData["attendedfrom"]);
        $this->database->bind(':attendedto',$updateData["attendedto"]);
        $this->database->bind(':awards',$updateData['awards']);
        $this->database->bind(':degree1',$updateData['degree1']);
        $this->database->bind(':degree2',$updateData['degree2']);
        $this->database->bind(':degree3',$updateData['degree3']);
        $this->database->bind(':degree4',$updateData['degree4']);

        try {
            $result = $this->database->execute();
            $this->loadByID($this->id);
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    protected function loadByID( $id ) {
        $this->database->query('SELECT * FROM schools WHERE id = :id');
        $this->database->bind(':id',$id);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
    }

    public function hasDegrees(){
        if($this->degree1 != "")
            return true;
        if($this->degree2 != "")
            return true;
        if($this->degree3 != "")
            return true;
        if($this->degree4 != "")
            return true;

        return false;
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->name = $row["name"];
        $this->location = $row["location"];
        $this->attendedfrom = $row["attendedfrom"];
        $this->attendedto = $row["attendedto"];
        $this->awards = $row["awards"];
        $this->degree1 = $row["degree1"];
        $this->degree2 = $row["degree2"];
        $this->degree3 = $row["degree3"];
        $this->degree4 = $row["degree4"];
        $this->skillset = $row["skillset"];
    }
}