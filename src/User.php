<?php

class User {

    private $id;
    private $name;
    private $lastName;
    private $email;
    private $hashedPassword;
    private $streetLine1;
    private $streetLine2;
    private $postalCode;
    private $city;

    public function __construct() {

        $this->id = -1;
        $this->name = "";
        $this->lastName = "";
        $this->email = "";
        $this->hashedPassword="";
        $this->streetLine1 = "";
        $this->streetLine2 = "";
        $this->postalCode = "";
        $this->city = "";
    }


    public function setName($newName) {
        if (is_string($newName)) {
            $this->name = $newName;
        }
        return $this;
    }
    
    public function setLastName($newLastName) {
        if (is_string($newLastName)) {
            $this->lastName = $newLastName;
        }
        return $this;
    }
    
    public function setEmail($newEmail) {
        if (is_string($newEmail)) {
            $this->email = $newEmail;
        }
        return $this;
    }

    public function setPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
    }
    
    public function setStreetLine1($newStreetLine1) {
        if (is_string($newStreetLine1)) {
            $this->streetLine1 = $newStreetLine1;
        }
        return $this;
    }
    
    public function setStreetLine2($newStreetLine2) {
        if (is_string($newStreetLine2)) {
            $this->streetLine2 = $newStreetLine2;
        }
        return $this;
    }
    
    public function setPostalCode($newPostalCode) {
        if (is_string($newPostalCode)) {
            $this->postalCode = $newPostalCode;
        }
        return $this;
    }
       
    public function setCity($newCity) {
        if (is_string($newCity)) {
            $this->city = $newCity;
        }
        return $this;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->Name;
    }
    
    public function getLastName() {
        return $this->lastName;
    }
       
    public function getEmail() {
        return $this->email;
    }
    
    public function getHashedPassword() {
        return $this->hashedPassword;
    }
    
    public function getStreetLine1() {
        return $this->streetLine1;
    }
    
    public function getStreetLine2() {
        return $this->streetLine2;
    }
    
    public function getPostalCode() {
        return $this->postalCode;
    }
    
    public function getCity() {
        return $this->city;
    }
    

    // zapisywanie usera do bazy danych
    
    public function saveUserToDB(mysqli $conn) {
        
        //jezeli brak id tzn. ze nowy uzytkownik
        if ($this->id == -1) {
          
            $sql = "INSERT INTO User"
                    . "(name, lastName, email, hashedPassword, streetLine1, streetLine2,"
                    . "postalCode, city ) "
                    . "VALUES ('$this->name', '$this->lastName', '$this->email', '$this->hashedPassword', "
                    . "'$this->streetLine1', '$this->streetLine2', '$this->postalCode', '$this->city')";          
  
            if ($conn->query($sql)==true) {
                $this->id = $conn->insert_id;
                return true;
            }
            
        } else {
            
            $sql = "UPDATE User "
                    . "SET name='$this->name', "
                    . "lastName = '$this->lastName',"
                    . "email='$this->email',  "
                    . "hashedPassword='$this->hashedPassword', "
                    . "streetLine1 ='$this->streetLine1', "
                    . "streetLine2 ='$this->streetLine2', "
                    . "postalCode ='$this->postalCode', "
                    . "city ='$this->city' "
                    . "WHERE id=$this->id";
            
         
            if ($conn->query($sql) == true) {
                return true;
            }
        }
        
        return false;
    }

        // wyswietlanie usera po id
    
    static public function loadUserById(mysqli $conn, $id) {
        
        $id = $conn->real_escape_string($id);
        if (!is_integer($id)) {
            return null;
        }
        
        $sql =    "SELECT * "
                . "FROM User "
                . "WHERE id=$id";
        
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadUser = new User();
            $loadUser->id = $row['id'];
            $loadUser->name = $row['name'];
            $loadUser->lastName = $row['lastName'];
            $loadUser->email = $row['email'];
            $loadUser->hashedPassword = $row['hashedPassword'];
            $loadUser->streetLine1 = $row['streetLine1'];
            $loadUser->streetLine2 = $row['streetLine2'];
            $loadUser->postalCode = $row['postalCode'];   
            $loadUser->city = $row['city'];

            return $loadUser;
        }
        return null;
    }
    
        // wyswietlanie wszystkich userow

    static public function loadAllUsers(mysqli $conn) {

        $sql =   "SELECT * "
                . "FROM User";
        $tab = [];
        $result = $conn->query($sql);
        if ($result && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadUser = new User();
                $loadUser->id = $row['id'];
                $loadUser->name = $row['name'];
                $loadUser->lastName = $row['lastName'];
                $loadUser->email = $row['email'];
                $loadUser->hashedPassword = $row['hashedPassword'];
                $loadUser->streetLine1 = $row['streetLine1'];
                $loadUser->streetLine2 = $row['streetLine2'];
                $loadUser->postalCode = $row['postalCode'];   
                $loadUser->city = $row['city'];
                $tab[]= $loadUser;
            }
        }
        return $tab;
    }

    //usuwanie usera
    
    public function deleteUser (mysqli $conn) {
       
        if ($this->id != -1) {
            $sql = "DELETE FROM User "
                    . "WHERE id=$this->id";
          
            if ($conn->query($sql) == true) {
                // nawet po usunięciu obiekt z właściwościami w bazie danych 
                // będzie istniał dlatego dajemy mu -1, strona się odświeża
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    //ladowanie user po id 
    /*
    static public function loadUserByEmail(mysqli $connection, $email) {
        $query = "SELECT * FROM User 
				WHERE email = '" . $connection->real_escape_string($email) . "'";

        $res = $connection->query($query);
        if ($res && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $user = new User();
            $user->id = $row['id'];
            $user->setUsername($row['name']);
            $user->setEmail($row['email']);
            $user->hashedPassword = $row['hashedPassword'];
            return $user;
        }
        return null;
    }

    static public function login(mysqli $connection, $email, $password) {
        $user = self::loadUserByEmail($connection, $email);
        if ($user && password_verify($password, $user->hashedPassword)) {
            return $user;
        } else {
            return false;
        }
    }

    static public function PasswordGetId(mysqli $connection, $email, $pass) {

        $query = "SELECT * FROM User WHERE email = '$email'";
        $result = $connection->query($query);
        if ($result == TRUE && $result->num_rows == 1) {

            $row = $result->fetch_assoc();
            $hashed_password = $row['hashedPassword'];
            if (password_verify($pass, $hashed_password)) {
                $id = $row['id'];
                return $id;
            }
        }
        return -1;
    }
*/
}
