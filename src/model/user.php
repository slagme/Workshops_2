<?php
require_once('src/util/db.php');
require_once('src/abstract/activeRecord.php');

class user extends activeRecord
{
    private $username;
    private $email;
    private $passwordHash;

    public function __construct(){
        parent::__construct();
        $this->email = '';
        $this->username = '';
        $this->passwordHash = '';
    }
    public function getId(){
        return $this->id;
    }
    public function getUsername(){
        return $this->username;
    }   
    public function getEmail(){
        return $this->email;
    }
    public function getPasswordHash(){
        return $this->passwordHash;
    }    
    public function setUsername($username){
        $this->username = $username;
    }    
    public function setEmail($email){
        $this->email = $email;
    }
    public function setPasswordHash($passwordHash){
        $this->passwordHash = md5($passwordHash);
    }
    public function save(){
        if (self::$db->conn != null) {
            if ($this->id == -1) {
                $sql = "INSERT INTO users (username, email, passwordHash) values ('$this->username', '$this->email', '$this->passwordHash')";

                $result = self::$db->conn->query($sql);

                if ($result == true) {
                    $this->id = self::$db->conn->lastInsertId();
                    return true;
                } else {
                    echo self::$db->conn->error;
                }
            } else {
                $sql = "UPDATE users SET username = '$this->username', email = '$this->email', passwordHash = '$this->passwordHash' where id = $this->id";

                $result = self::$db->conn->query($sql);

                if ($result == true) {
                    return true;
                }
            }
        } else {
            echo "Brak polaczenia\n";
        }
        return false;}

    static public function loadById($id){
        self::connect();
        $sql = "SELECT * FROM users WHERE id=$id";
        $result = self::$db->conn->query($sql);
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->passwordHash = $row['passwordHash'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;}

    static public function loadAll(){
        self::connect();
        $sql = "SELECT * FROM users";
        $returnTable = [];
        if ($result = self::$db->conn->query($sql)) {
            foreach ($result as $row){
                $loadedUser = new user();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->passwordHash = $row['passwordHash'];
                $loadedUser->email = $row['email'];
                $returnTable[] = $loadedUser;
            }
        }
        return $returnTable;}

    public function delete(){
        if($this->id != -1){
            if(self::$db->conn->query("DELETE FROM users WHERE id=$this->id")){
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;}
}
