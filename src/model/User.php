<?php

class user extends activeRecord
{
    private $username;
    private $email;
    private $passwordHash;

    public function __construct(){//3 parametry
        parent::__construct();
        $this->email = '';
        $this->username = '';
        $this->passwordHash = '';}

    public function getId(){
        return $this->id;}

    public function getUsername(){
        return $this->username;}

    public function getEmail(){
        return $this->email;}

    public function getPasswordHash(){
        return $this->passwordHash;}

    public function setUsername($username){
        $this->username = $username;}

    public function setEmail($email){
        $this->email = $email;}

    public function setPasswordHash($passwordHash){
        $this->passwordHash = md5($passwordHash); }

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
        return false;   
    }

    static public function loadById($id){
        self::connect();
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt=self::$db->conn->prepare($sql);
        $result = $stmt->execute(['id' => $id]);
        if ($result && $stmt->rowCount() == 1) { 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->passwordHash = $row['passwordHash'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
        
    }

    static public function loadByEmail($email){
        self::connect();
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt=self::$db->conn->prepare($sql);
        $result=$stmt->execute(['email' => $emal]);
        if ($result && $stmt->rowCount()==1) {
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser= new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->passwordHash = $row['passwordHash'];
            $loadedUser->email = $row['email'];
            return $loadedUser; 
            }
        return null;    
        }
        
        static public function loadByUsername($username){
        self::connect();
        $sql = "SELECT * FROM users WHERE username=:username";
        $stmt=self::$db->conn->prepare($sql);
        $result=$stmt->execute(['username' => $username]);
        if ($result && $stmt->rowCount()==1) {
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser= new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->passwordHash = $row['passwordHash'];
            $loadedUser->email = $row['email'];
            return $loadedUser; 
            }
        return null;    
        }
        
        static public function loadAll(){
            self::connect();
            $sql="SELECT * FROM users";
            $returnTable=[];
            if ($result = self::$db->conn->query($sql)){
                foreach ($result as $row){
                    $loadedUser=new user();
                    $loadedUser->id = $row['id'];    
                    $loadedUser->username = $row['username'];    
                    $loadedUser->passwordHash = $row['passwordHash'];    
                    $loadedUser->email = $row['email'];
                    $returnTable[]=$loadedUser;
                }
            }
            return $returnTable;
        }

    public function delete(){
        if($this->id != -1){//jeśli nie jest użytkownikiem tymczasowym
            if(self::$db->conn->query("DELETE FROM Users WHERE id=$this->id")){//usuń jeżeli nie jest id -1;
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }
}    

$obj1 = new User();
$obj1->setUsername('Janusz z Obornik '.rand(0,9));
$obj1->setEmail('janusz14'.rand(0,9).'@wp.pl');
$obj1->setpasswordHash('1234'.rand(0,9));
$obj1->saveToDb();

//$obj1 = user::loadUserById(4);
//$obj1->setUsername('Andrzej');
// $obj1->saveToDb();
// $obj1->delete();

User::connect();
var_dump(User::loadAll);

?>