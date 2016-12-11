<?php

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;
    private $information;

    public function __construct() {

        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashedPassword = "";
        $this->information = "";
    }

    public function getId() {
        return $this->id;
    }

    public function setUsername($NewUsername) {
        $this->username = $NewUsername;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
    }

    public function getHashedPassword() {
        return $this->hashedPassword;
    }

    public function setEmail($NewEmail) {
        $this->email = $NewEmail;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setInformation($NewInformation) {
        $this->information = $NewInformation;
    }

    public function getInformation() {
        return $this->information;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            //insert nowego użytkownika do bazy danych
            $sql = "INSERT INTO User(username, email, hashedPassword, information) VALUES ('$this->username', '$this->email', '$this->hashedPassword', '$this->information')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE User SET username='$this->username', email='$this->email',  hashedPassword='$this->hashedPassword', information='$this->information' WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param mysqli $connection
     * @param type $id
     * @return \User
     */
    static public function loadUserById(mysqli $connection, $id) {
        $sql = "SELECT * FROM User WHERE id=$id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashedPassword'];
            $loadedUser->email = $row['email'];
            $loadedUser->information = $row['information'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadAllUsers(mysqli $connection) {

        $sql = "SELECT * FROM User";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashedPassword'];
                $loadedUser->email = $row['email'];
                $loadedUser->information = $row['information'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete(mysqli $connection) {
        if ($this->id != -1) {
            $sql = "DELETE FROM User WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                // nawet po usunięciu obiekt z właściwościami w bazie danych będzie istniał dlatego dajemy mu -1, strona się odświeża
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

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

}
