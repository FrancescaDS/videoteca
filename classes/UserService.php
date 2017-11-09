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

    /**
     * The database connection object (PDO)
     */
    protected $db;

    /**
     * Array containg the user's data (email, name, etc.)
     * NB: This probably shouldn't be stored in this object, but we want to
     * keep things relatively simple at this stage.
     */
    protected $data;

    /**
     * The constructor expects to be passed an instance of a PDO object
     * (notice the type hint). It stores the passed object in the $db property.
     */
    public function __construct(PDO $connection_object) {
        $this->db = $connection_object;
    }

    /**
     * This function registers a new user on the system (i.e. it creates
     * a new record in the database). Note how it does not check if the
     * email address already exists in the database. This would most likely
     * be carried out by validation code, before this method is called.
     * Because it is not good to store passwords as plain text, the built-in
     * "password_hash" function has been used to create a hashed copy which
     * is safe to store.
     */
    public function register($email, $password, $firstname, $surname) {
        // Query to insert the user's data
        $query = "INSERT INTO users (email, password, firstname, surname)
        VALUES (:email, :password, :firstname, :surname)";

        // Prepare the statement
        $statement = $this->db->prepare($query);

        // Values to use with prepared statement
        $query_data = [
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':firstname' => $firstname,
            ':surname' => $surname
        ];

        // Execute the statement
        if ($statement->execute($query_data)) {
            // If query successful, add user's data to the $data object property
            $this->data = [
                'id' => $this->db->lastInsertId(),
                'email' => $email,
                'firstname' => $firstname,
                'surname' => $surname,
            ];
            return true;
        } else {
            return false;
        }

    }

    /**
     * This function attempts to log a user into the system.
     * It expects to be passed the user's email and password as plain text
     * (i.e. not hashed or encrypted in any way).
     * First, it selects records that match the passed in email address
     * (there should only be one record returned). It then compares the
     * stored password (which is hashed) with the plain text password
     * that was passed to the function. The built-in "password_verify"
     * function makes it trivial to do this.
     */
    public function login($user_email, $user_password) {
        $statement = $this->selectUser($user_email);

        // We should only get one record in our results
        if ($statement->rowCount() == 1) {

            // Get record data as an associative array
            $stored_data = $statement->fetch(PDO::FETCH_ASSOC);

            // Compare plain text password passed to function with hashed
            // version stored in database
            if (password_verify($user_password, $stored_data['password'])) {
                // we don't need/want to store password in object... so we unset it
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

    /*
     * Public "getter" method so outside code can access the user's data
     * (which is a protected property). As mentioned above, we probably
     * shouldn't store this data in this object... a dedicated "User"
     * object would be preferable...
     */
    public function getData() {
        return $this->data;
    }

    public function existsEmail($email){
        return ($this->selectUser($email)->rowCount() == 1);
    }
    
    private function selectUser($email){
        // Query to select rows with correct email value
        $query = "SELECT * FROM users WHERE email = :email";

        // Prepare the statement
        $statement = $this->db->prepare($query);

        // Value to use with prepared statement
        $query_data = [':email' => $email];

        // Execute the query
        $statement->execute($query_data);
        
        return $statement;
    }
    
}
?>
