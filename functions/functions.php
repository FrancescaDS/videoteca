<?php
//AUTOLOADER
function myAutoloader($class_name){
    $path = 'classes/' . $class_name . '.php';
    include $path;
}

//register the function with PHP
spl_autoload_register('myAutoloader');

session_start();
function UserLogIn($Username, $Password){
    global $User;
    if (!empty($Username) and !empty($Password))
    {
        $User = NULL;
        $cheQuery ="SELECT IDUser, Username, Password FROM Users WHERE Username='" . $Username . "' AND Password='" . $Password ."'";
        if ($result = mysqli_query(GetMyConnection(), $cheQuery)) {
            $User = $result->fetch_assoc();
            
            $_SESSION['IDUser'] = $User["IDUser"];
            $_SESSION['Username'] = $Username;
            $_SESSION['Password'] = $Password;
        }
    }
    return $User;
}

function GetUser()
{
    global $User;
    if (empty($User)){
        if (!empty($_SESSION['IDUser'])){
            $User["IDUser"]=$_SESSION['IDUser'];
            $User["Username"]=$_SESSION['Username'];
            $User["Password"]=$_SESSION['Password'];
        }
    }
    return $User;
}

function UserLogOut(){
   global $User;
   unset($User);
   if (!empty($_SESSION['IDUser'])){
       unset($_SESSION['IDUser']);
   }
   if (!empty($_SESSION['Username'])){
       unset($_SESSION['Username']);
   }
   if (!empty($_SESSION['Password'])){
       unset($_SESSION['Password']);
   }
   if (!empty($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
    }
    if (!empty($_SESSION['user'])){
        unset($_SESSION['user']);
    }
}

function getAllDirectors(){
    return getAllPeople('dir');
}

function getAllActors(){
    return getAllPeople('act');
}


//return an array of person objects
function getAllPeople($what){
    $db = new Mydb();
    $connection_object = $db->mysql;
    try {
        $result = [];
        if ($what === 'dir'){
            $sql = "SELECT DISTINCT people.id_person, people.name, people.surname FROM people "
                . "INNER JOIN directors ON directors.id_person = people.id_person "
                . "ORDER BY people.surname, people.name";
        } else {
            $sql = "SELECT DISTINCT people.id_person, people.name, people.surname FROM people "
                . "INNER JOIN actors ON actors.id_person = people.id_person "
                . "ORDER BY people.surname, people.name";
        }
        $stat = $connection_object->prepare($sql);
        $stat->execute();
        if ($stat->rowCount() >= 1) {
            $result = $stat->fetchAll();
        }
        return $result;
    } catch (Exception $ex) {
        exit($ex->getMessage());
    }    
}

//return an array
function getAllMovies(){
    $db = new Mydb();
    $connection_object = $db->mysql;
    try {
        $result = [];
        $sql = "SELECT * FROM movies "
            . "ORDER BY movies.year, movies.title";
        $stat = $connection_object->prepare($sql);
        $stat->execute();
        $result = $stat;
        if ($stat->rowCount() >= 1) {
            $result = $stat->fetchAll();
        }
        return $result;
    } catch (Exception $ex) {
        exit($ex->getMessage());
    }    
}


function upload($IDFilm)
{
  $result = false;
  $immagine = '';
  $size = 0;
  $type = '';
  $Name = '';
  $max_size = 100000; //100K
    //$max_size = $_POST['MAX_FILE_SIZE'];
  $result = @is_uploaded_file($_FILES['file']['tmp_name']);
  if (!$result)
  {
    //problemi
      return -2;
  }else{
    $size = $_FILES['file']['size'];
    if ($size > $max_size)
    {
        //file troppo grande
        return -1;
    } else{
        $IDPoster = 0;
        $cheQuery ="SELECT IDPoster FROM Posters WHERE IDFilm=" . $IDFilm ;
        if ($result = mysqli_query(GetMyConnection(), $cheQuery)) {
            $row = $result->fetch_assoc();
            $IDPoster = $row['IDPoster'];
        }
        $type = $_FILES['file']['type'];
        $Name = $_FILES['file']['name'];
        $immagine = @file_get_contents($_FILES['file']['tmp_name']);
        $immagine = addslashes ($immagine);
        if ($IDPoster == 0){
            $cheQuery = "INSERT INTO Posters (Name, Size, Type, Poster, IDFilm) VALUES ('$Name','$size','$type','$immagine', '$IDFilm')";   
        }else{
            $cheQuery = "UPDATE Posters SET Name='$Name', Size='$size', Type='$type', Poster='$immagine'";
            $cheQuery = $cheQuery . " WHERE IDPoster=". $IDPoster;
        }
        mysqli_query(GetMyConnection(), $cheQuery);
        return 1;
    }
  }
}



