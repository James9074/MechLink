<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

class Skillset
{
    public $id;
    public $automobiletype;
    public $location;
    public $restoredfrom;
    public $restoredto;
    public $award1;
    public $award2;
    public $award3;
    public $award4;
    public $skills;
    public $username;
    public $dateposted;
    public $schools;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function createNew($newSkillset){
        $database = new Database();
        $database->query('INSERT INTO skillsets (automobiletype, location, restoredfrom, restoredto, award1, award2, award3, award4, skills, username, dateposted) VALUES (:automobiletype, :location, :restoredfrom, :restoredto, :award1, :award2, :award3, :award4, :skills, :username, :dateposted)');
        $database->bind(':automobiletype',$newSkillset["automobiletype"]);
        $database->bind(':location',$newSkillset["location"]);
        $database->bind(':restoredfrom',$newSkillset["restoredfrom"]);
        $database->bind(':restoredto',$newSkillset["restoredto"]);
        $database->bind(':award1',$newSkillset['award1']);
        $database->bind(':award2',$newSkillset['award2']);
        $database->bind(':award3',$newSkillset['award3']);
        $database->bind(':award4',$newSkillset['award4']);
        $database->bind(':skills',$newSkillset["skills"]);
        $database->bind(':username',$newSkillset["username"]);
        $database->bind(':dateposted',date("Y-m-d"));

        try {
            $result = $database->execute();
            return Skillset::withID($database->lastInsertId());
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
        $this->database->query('UPDATE skillsets SET automobiletype = :automobiletype, location = :location, restoredfrom = :restoredfrom, restoredto = :restoredto, award1 = :award1, award2 = :award2, award3 = :award3, award4 = :award4, skills = :skills, username = :username, dateposted = :dateposted WHERE id = :id');
        $this->database->bind(':id',$this->id);
        $this->database->bind(':automobiletype',$updateData["automobiletype"]);
        $this->database->bind(':location',$updateData["location"]);
        $this->database->bind(':restoredfrom',$updateData["restoredfrom"]);
        $this->database->bind(':restoredto',$updateData["restoredto"]);
        $this->database->bind(':award1',$updateData['award1']);
        $this->database->bind(':award2',$updateData['award2']);
        $this->database->bind(':award3',$updateData['award3']);
        $this->database->bind(':award4',$updateData['award4']);
        $this->database->bind(':skills',$updateData["skills"]);
        $this->database->bind(':username',$this->username);
        $this->database->bind(':dateposted',$this->dateposted);

        try {
            $result = $this->database->execute();
            $this->loadByID($this->id);
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    public function delete(){
        $this->database->query('DELETE from skillsets WHERE id = :id');
        $this->database->bind(':id',$this->id);

        try {
            $result = $this->database->execute();
            return true;
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    protected function loadByID( $id ) {
        $this->database->query('SELECT * FROM skillsets WHERE id = :id');
        $this->database->bind(':id',$id);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1) {
                $this->fill($row);
                $this->getSchools();
            }
        }catch (PDOException $e) {
            //Error..
        }
    }

    public function getSchools(){
        if (isset($this->schools))
            return $this->schools;
        $this->database->query('SELECT id FROM schools WHERE skillset = :skillset');
        $this->database->bind(':skillset',$this->id);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0) {
                $this->schools = [];
                foreach ($rows as $row) {
                    array_push($this->schools, School::withID($row['id']));
                }
            }
            else
                $this->schools = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->schools;
    }

    public function hasAwards(){
        if($this->award1 != "")
            return true;
        if($this->award2 != "")
            return true;
        if($this->award3 != "")
            return true;
        if($this->award4 != "")
            return true;

        return false;
    }

    protected function fill( array $row ) {
        $this->id = $row['id'];
        $this->automobiletype = $row["automobiletype"];
        $this->location = $row["location"];
        $this->restoredfrom = $row["restoredfrom"];
        $this->restoredto = $row["restoredto"];
        $this->award1 = $row["award1"];
        $this->award2 = $row["award2"];
        $this->award3 = $row["award3"];
        $this->award4 = $row["award4"];
        $this->skills = $row["skills"];
        $this->username = $row["username"];
        $this->dateposted = $row["dateposted"];
    }
}