<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");

class User
{
    public $id;
    public $rlname;
    public $username;
    public $category;
    public $email;
    public $location;
    public $profile_id;
    public $gender;
    public $website;
    public $country;
    public $userlevel;
    public $avatar;
    public $ip;
    public $signup;
    public $lastlogin;
    public $notescheck;
    public $joindate;
    public $lastsession;
    public $description;
    public $status;
    public $activated;
    private $friends;
    private $skillsets;
    private $projects;
    private $photos;
    private $blockedUsers;
    private $database;

    function __construct() {
        $this->database = new Database();
    }

    public static function withUsername($username){
        $instance = new self();
        $instance->loadByUsername( $username );
        return $instance;
    }

    public function update($updateData){
        $this->database->query('UPDATE users SET rlname = :rlname, category = :category, location = :location, email = :email, gender = :gender, website = :website, country = :country, userlevel = :userlevel, avatar = :avatar, ip = :ip, signup = :signup, lastlogin = :lastlogin,notescheck = :notescheck,activated = :activated, description = :description, status = :status WHERE username = :username');
        $this->database->bind(':rlname',isset($updateData["rlname"]) ? $updateData["rlname"] : $this->rlname);
        $this->database->bind(':category',isset($updateData["category"]) ? $updateData["category"] : $this->category);
        $this->database->bind(':location',isset($updateData["location"]) ? $updateData["location"] : $this->location);
        $this->database->bind(':username',$this->username);
        $this->database->bind(':email',isset($updateData["email"]) ? $updateData["email"] : $this->email);
        $this->database->bind(':gender',isset($updateData["gender"]) ? $updateData["gender"] : $this->gender);
        $this->database->bind(':website',isset($updateData["website"]) ? $updateData["website"] : $this->website);
        $this->database->bind(':country',isset($updateData["country"]) ? $updateData["country"] : $this->country);
        $this->database->bind(':userlevel',isset($updateData["userlevel"]) ? $updateData["userlevel"] : $this->userlevel);
        $this->database->bind(':avatar',isset($updateData["avatar"]) ? $updateData["avatar"] : $this->avatar);
        $this->database->bind(':ip',isset($updateData["ip"]) ? $updateData["ip"] : $this->ip);
        $this->database->bind(':signup',isset($updateData["signup"]) ? $updateData["signup"] : $this->signup);
        $this->database->bind(':lastlogin',isset($updateData["lastlogin"]) ? $updateData["lastlogin"] : $this->lastlogin);
        $this->database->bind(':notescheck',isset($updateData["notescheck"]) ? $updateData["notescheck"] : $this->notescheck);
        $this->database->bind(':activated',isset($updateData["activated"]) ? $updateData["activated"] : $this->activated);
        $this->database->bind(':description',isset($updateData["description"]) ? $updateData["description"] : $this->description);
        $this->database->bind(':status',isset($updateData["status"]) ? $updateData["status"] : $this->status);

        try {
            $result = $this->database->execute();
            $this->loadByUsername($this->username);
        }catch (PDOException $e) {
            //Error...
            return $e;
        }
    }

    protected function loadByUsername( $username ) {
        $this->database->query('SELECT * FROM users WHERE username = :username');
        $this->database->bind(':username',$username);
        try {
            $row = $this->database->single();
            if(sizeof($row) > 1)
                $this->fill( $row );
        }catch (PDOException $e) {
            //Error..
        }
    }

    public function getFriends(){
        if (isset($this->friends))
            return $this->friends;
        $this->database->query('SELECT * FROM friends WHERE user1 = :username or user2 = :username');
        $this->database->bind(':username',$this->username);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0)
                $this->friends = $rows;
            else
                $this->friends = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->friends;
    }

    public function getBlockedUsers(){
        if (isset($this->blockedUsers))
            return $this->blockedUsers;
        $this->database->query('SELECT * FROM blockedusers WHERE blocker = :username or blockee = :username');
        $this->database->bind(':username',$this->username);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0)
                $this->blockedUsers = $rows;
            else
                $this->blockedUsers = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->blockedUsers;
    }

    public function getSkillsets(){
        if (isset($this->skillsets))
            return $this->skillsets;
        $this->database->query('SELECT id FROM skillsets WHERE username = :username');
        $this->database->bind(':username',$this->username);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0) {
                $this->skillsets = [];
                foreach ($rows as $row) {
                    array_push($this->skillsets, Skillset::withID($row['id']));
                }
            }
            else
                $this->skillsets = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->skillsets;
    }

    public function getProjects(){
        if (isset($this->projects))
            return $this->projects;
        $this->database->query('SELECT id FROM projects WHERE username = :username');
        $this->database->bind(':username',$this->username);
        try {
            $rows = $this->database->resultset();
            if($this->database->rowCount() > 0) {
                $this->projects = [];
                foreach ($rows as $row) {
                    array_push($this->projects, Project::withID($row['id']));
                }
            }
            else
                $this->projects = [];
        }catch (PDOException $e) {
            //Error..
        }
        return $this->projects;
    }

    public function getPhotos(){
        if (isset($this->photos))
            return $this->photos;
        $this->database->query('SELECT id FROM photos WHERE username = :username');
        $this->database->bind(':username',$this->username);
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
        $this->id = $row["id"];
        $this->rlname = $row["rlname"];
        $this->category = $row["category"];
        $this->username = $row["username"];
        $this->email = $row["email"];
        $this->location = $row["location"];
        $this->profile_id = $row["id"];
        $this->gender = $row["gender"];
        $this->website = $row["website"];
        $this->country = $row["country"];
        $this->userlevel = $row["userlevel"];
        $this->avatar = $row["avatar"];
        $this->ip = $row["ip"];
        $this->signup = $row["signup"];
        $this->lastlogin = $row["lastlogin"];
        $this->notescheck = $row["notescheck"];
        $this->joindate = $row["signup"];
        $this->lastsession = $row["lastlogin"];
        $this->description = $row["description"];
        $this->status = $row["status"];
        $this->activated = $row["activated"];
    }
}