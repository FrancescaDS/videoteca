<?php

class Movie{
    //data[] = array where keys are names of fields
    //id_movie, title, year
    protected $data = [];
    
    protected $connection_object;
    
    public function __construct($id = 0) {
        try {
            $db = new Mydb();
            $this->connection_object = $db->mysql;
            if (is_int($id) && $id != 0){
                $this->selectMovie($id);
            } else{
                $this->data =[];
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    private function selectMovie($id){
        try {
            $sql = "SELECT * FROM movies WHERE id_movie=".$id;
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() === 1) {
                $this->data = $stat->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getDirectors(){
        return $this->getPeople('dir');    
    }
    
    public function getActors(){
        return $this->getPeople('act');
    }
    
    protected function getPeople($what){
        try {
            $result = [];
            if ($what === 'dir'){
                $sql = "SELECT * FROM people "
                . "INNER JOIN directors ON directors.id_person = people.id_person "
                . "WHERE directors.id_movie = " . $this->data['id_movie']
                . " ORDER BY people.surname, people.name";
            } else {
                $sql = "SELECT people.*, actors.character_name FROM people "
                . "INNER JOIN actors ON actors.id_person = people.id_person "
                . "WHERE actors.id_movie = " . $this->data['id_movie']
                . " ORDER BY people.surname, people.name";
            }
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() >= 1) {
                $result = $stat->fetchAll();
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function getDirectorsNot(){
        return $this->getPeopleNot('dir');    
    }
    
    public function getActorsNot(){
        return $this->getPeopleNot('act');
    }
    
    protected function getPeopleNot($what) {
        try{
            if ($what === 'dir'){
                $table = "directors";
            } else {
                $table = "actors";
            }
            $sql = "SELECT id_person, name, surname FROM (SELECT id_person, name, surname, "
                ."(SELECT id_person FROM ".$table." WHERE id_movie = ".$this->data['id_movie']." and id_person=people.id_person) "
                ."AS present FROM people) as elenco WHERE elenco.present is null "
                ."ORDER BY surname, name";
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() >= 1) {
                $result = $stat->fetchAll();
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function isUnique($title, $year){
        try{
            $result = false;
            $sql = "SELECT * FROM movies WHERE title='".$title."' AND year=".$year;
            if (isset($this->data['id_movie'])){
                $sql = $sql . " AND id_movie<>".$this->data['id_movie'] ;
            }
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() === 0) {
                $result = true;
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function insertUpdate($title, $year) {
        try{
            if (isset($this->data['id_movie'])){
                return $this->updateMovie($title, $year);
            } else {
                return $this->insertMovie($title, $year);
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    protected function insertMovie($title, $year) {
        try{
            $sql = "INSERT INTO movies (title, year) VALUES ('".$title."',".$year.")";
            $stat = $this->connection_object->prepare($sql);
            $result = $stat->execute();
            if ($result) {
                $this->data = [
                   'id_movie' => $this->connection_object->lastInsertId(),
                   'title' => $title,
                   'year' => $year
               ];    
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    protected function updateMovie($title, $year) {
        try{
            $sql = "UPDATE movies SET title='".$title."', year=".$year." WHERE id_movie=".$this->data['id_movie'];
            $stat = $this->connection_object->prepare($sql);
            $result = $stat->execute();
            if ($result) {
                $this->data['title'] = $title;
                $this->data['year'] = $year;  
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function insertDirector($id_person){
        return $this->insertPerson("dir", $id_person);
    }
    
    public function insertActor($id_person, $character_name){
        return $this->insertPerson("act", $id_person, $character_name);
    }
    
    protected function insertPerson($what, $id_person, $character_name=""){
        try{
            if ($what == "dir"){
                $table = "directors";
            }else{
                $table = "actors";
            }
            $id_movie = $this->data['id_movie'];
            $sql = "SELECT * FROM " . $table
                . " WHERE id_movie=".$id_movie." AND id_person=".$id_person;
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() === 0) {
                if ($what == "dir"){
                    $sql = "INSERT INTO directors (id_movie, id_person) "
                    . "VALUES (".$id_movie.",".$id_person.")";
                }else{
                    $sql = "INSERT INTO actors (id_movie, id_person, character_name) "
                    . "VALUES (".$id_movie.",".$id_person.", '".$character_name."')";
                }
                $stat = $this->connection_object->prepare($sql);
                $result = $stat->execute();
            } else{
                $result = false;
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function deleteDirector($id_person){
        return $this->deletePerson("dir", $id_person);
    }
    
    public function deleteActor($id_person){
        return $this->deletePerson("act", $id_person);
    }
    
    public function deletePerson($what, $id_person){
        try{
            if ($what == "dir"){
                $table = "directors";
            }else{
                $table = "actors";
            }
            $id_movie = $this->data['id_movie'];
            $sql = "DELETE FROM ".$table." WHERE "
                . "id_movie=".$id_movie." AND id_person=".$id_person;
            $stat = $this->connection_object->prepare($sql);
            $result = $stat->execute();
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
}

