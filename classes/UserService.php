<?php
/**
 * This class encapsulates some of the functionality required to manage
 * and authenticate users in an application. It also demonstrates dependency
 * injection which we have seen previously on the course. But this time,
 * the dependency is the datbase connection object.
 * Read the comments for details of what is occurring in the methods and
 * see index.php for examples of how it might be used.
 * NB: Before running the code in index.php, you should make sure the "users"
 * table exists in your database (see users.sql for a setup script which
 * you can run via Adminer or phpMyAdmin).
 */

class UserService {

    protected $connection_object;

    //data[] = array where keys are names of fields
    //id_user, name, surname, email, password
    protected $data;

    public function __construct() {
        $db = new Mydb();
        $this->connection_object = $db->mysql;
    }

    public function register($email, $password, $name, $surname) {
        $sql = "INSERT INTO users (email, password, name, surname)
        VALUES (:email, :password, :name, :surname)";
        $stat = $this->connection_object->prepare($sql);
        $query_data = [
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':name' => $name,
            ':surname' => $surname
        ];
        if ($stat->execute($query_data)) {
            $this->data = [
                'id_user' => $this->connection_object->lastInsertId(),
                'email' => $email,
                'name' => $name,
                'surname' => $surname,
            ];
            return true;
        } else {
            return false;
        }
    }

    public function update($id_user, $email, $name, $surname, $password = "") {
        $sql = "UPDATE users SET "
               . "email='".$email."', "
               . "name='".$name."', "
               . "surname='".$surname."' ";
        if ($password <> ""){
            $sql = $sql . ", password='".password_hash($password, PASSWORD_BCRYPT)."' ";
        }
        $sql = $sql . " WHERE id_user=".$id_user;
        $stat = $this->connection_object->prepare($sql);
        if ($stat->execute()) {
            $this->data['id_user'] = $id_user;
            $this->data['email'] = $email;
            $this->data['name'] = $name;
            $this->data['surname'] = $surname;
            return true;
        } else {
            return false;
        }
    }
    
    public function login($user_email, $user_password) {
        $stat = $this->selectUser($user_email);
        if ($stat->rowCount() == 1) {
            // Get record data as an associative array
            $stored_data = $stat->fetch(PDO::FETCH_ASSOC);
            // Compare plain text password passed to function with hashed
            // version stored in database
            if (password_verify($user_password, $stored_data['password'])) {
                // unset password from $data
                unset($stored_data['password']);
                // Transfer DB data to object's $data property
                $this->data = $stored_data;
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function getData() {
        return $this->data;
    }

    public function existsEmail($email, $id = 0){
        if ($id === 0){
            return ($this->selectUser($email)->rowCount() == 1);
        } else {
            $sql = "SELECT * FROM users WHERE email='".$email."' AND id_user<>".$id;
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            return ($stat->rowCount() == 1);
        }
    }
    
    private function selectUser($email){
        $sql = "SELECT * FROM users WHERE email = :email";
        $stat = $this->connection_object->prepare($sql);
        $query_data = [':email' => $email];
        $stat->execute($query_data);
        return $stat;
    }
    
    public function logout(){
        if (!empty($_SESSION['logged_in'])){
            unset($_SESSION['logged_in']);
        }
        if (!empty($_SESSION['user'])){
            unset($_SESSION['user']);
        }
        if (isset($this->data)){
            $this->data = [];
        }
     }
    
}
?>
