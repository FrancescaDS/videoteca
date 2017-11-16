<?php
session_start();
//AUTOLOADER
function myAutoloader($class_name){
    $path = 'classes/' . $class_name . '.php';
    include $path;
}

//register the function with PHP
spl_autoload_register('myAutoloader');

function getAllDirectors($limit_from = 0, $res_per_page = 0){
    return getAllPeople('dir', $limit_from, $res_per_page);
}

function getAllActors($limit_from = 0, $res_per_page = 0){
    return getAllPeople('act', $limit_from, $res_per_page);
}


//return an array
function getAllPeople($what, $limit_from=0, $res_per_page=0){
    $db = new Mydb();
    $connection_object = $db->mysql;
    try {
        $result = [];
        $sql = "SELECT DISTINCT people.id_person, name, surname, dob FROM people ";
        if ($what === 'dir'){
            $sql = $sql . "INNER JOIN directors ON directors.id_person = people.id_person ";
        } else {
            $sql = $sql . "INNER JOIN actors ON actors.id_person = people.id_person "; 
        }
        $sql = $sql . "ORDER BY people.surname, people.name";
        if ($res_per_page>0){
            $sql = $sql . " LIMIT $limit_from, $res_per_page";
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
function getAllMovies($limit_from = 0, $res_per_page = 0){
    $db = new Mydb();
    $connection_object = $db->mysql;
    try {
        $result = [];
        $sql = "SELECT * FROM movies "
            . "ORDER BY movies.year, movies.title ";
        if ($res_per_page>0){
            $sql = $sql . " LIMIT $limit_from, $res_per_page";
        } 
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

function getPagination($rows){
    $res_per_page = 5;
    $tot_res = count($rows);
    $num_pages = ceil($tot_res/$res_per_page);
    if (!isset($_GET['page'])){
        $present_page = 1;
    } else {
        $present_page = trim(filter_input(INPUT_GET, 'page'));
        if ((!is_numeric($present_page)) || ($present_page>$num_pages) || ($present_page<1)){
            $present_page = 1;
        }
    }
    $limit_from = ($present_page-1)*$res_per_page;
    $previous_page = $present_page-1;
    $next_page = $present_page+1;
    $result = [
        'tot_res' => $tot_res,
        'num_pages' => $num_pages,
        'present_page' => $present_page,
        'previous_page' => $previous_page,
        'next_page' => $next_page,
        'limit_from' => $limit_from,
        'res_per_page' => $res_per_page
    ];
    return $result;
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



