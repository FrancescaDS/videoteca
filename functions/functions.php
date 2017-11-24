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

function getAllPeopleAll($limit_from = 0, $res_per_page = 0){
    return getAllPeople('', $limit_from, $res_per_page);
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
        } elseif ($what === 'act'){
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

function uploadImage($id, $dir){
    $result[0] = false;
    $result[1] = '';
    
    do {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            if ($_FILES['image']['size'] > 2000000) {
                $result[1] = "<p>The image must be max 2Mb</p>";
                break;
            }
        
            list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name']);
            if ($width > $height) {
                $result[1] = "<p>The image must be vertical or squared</p>";
                break;
            }
          
            if (($type!=1) && ($type!=2) && ($type!=3)) {
                $result[1] = "<p>No right format image (jpg)</p>";
                break;
            }
          
            $temp = explode('.', $_FILES['image']['name']);
            $ext = end($temp);
            
            resizeImage($_FILES['image']['tmp_name'], 'archive/'.$dir.'/'.$id.'.'.$ext, 200, 300);
            
        } else {
            $result[1] = "<p>No file selected</p>";
            break;
        }
        
    } while (false);
    
    if ($result[1]==''){
        $result[0]=true;
    }
    return $result;
}

function resizeImage($imagePath, $newImage, $width, $height) {
    $img = new SmartImage($imagePath);
    $img->resize($width, $height, true);
    $img->saveImage($newImage);
}
