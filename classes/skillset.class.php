<?php
include_once("includes/db_conn.php");

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

    protected function loadByID( $id ) {
        $this->database->query('SELECT * FROM skillsets WHERE id = :id');
        $this->database->bind(':id',$id);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
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