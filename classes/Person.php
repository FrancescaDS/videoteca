<?php

class Person{
    //data[] = array where keys are names of fields
    //id_person, name, surname, dob
    protected $data = [];
    
    protected $connection_object;
    
    public function __construct($id = 0) {
        try {
            if ($id != 0){
                $db = new Mydb();
                $this->connection_object = $db->mysql;
                
                $this->selectPerson($id);
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    private function selectPerson($id){
        try {
            $sql = "SELECT * FROM people WHERE id_person=".$id;
            $stat = $this->connection_object->prepare($sql);
            $stat->execute();
            if ($stat->rowCount() == 1) {
                $stored_data = $stat->fetch(PDO::FETCH_ASSOC);
                $this->data = $stored_data;
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getDirectedMovies(){
        return $this->getMovies('dir');    
    }
    
    public function getPlayedMovies(){
        return $this->getMovies('act');
    }
    
    protected function getMovies($what){
        try {
            $result = [];
            if ($what === 'dir'){
                $sql = "SELECT * FROM movies "
                . "INNER JOIN directors ON directors.id_movie = movies.id_movie "
                . "WHERE directors.id_person = " . $this->data['id_person']
                . " ORDER BY year, title";
            } else {
                $sql = "SELECT movies.*, actors.character_name FROM movies "
                . "INNER JOIN actors ON actors.id_movie = movies.id_movie "
                . "INNER JOIN people ON people.id_person = actors.id_person "
                . "WHERE actors.id_person = " . $this->data['id_person']
                . " ORDER BY year, title";
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
    
    public function insertUpdate($name, $surname, $dob, $place, $dod) {
        try{
            if (isset($this->data['id_person'])){
                return $this->updatePerson($name, $surname, $dob, $place, $dod);
            } else {
                return $this->insertPerson($name, $surname, $dob, $place, $dod);
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    protected function insertPerson($name, $surname, $dob, $place, $dod) {
        try{
            $sql = "INSERT INTO people (name, surname, dob, place, dod) "
                . "VALUES ('".$name."','".$surname."',"
                . "'" . STR_TO_DATE($dob, '%d/%m/%Y' )."','".$place."'";
            if ($dod <> ""){
                $sql = $sql . ", '" . STR_TO_DATE($dod, '%d/%m/%Y' )."' )";
            }else{
                $sql = $sql . ", '0000-00-00' )";
            }
            $stat = $this->connection_object->prepare($sql);
            $result = $stat->execute();
            if ($result) {
                $id = $this->connection_object->lastInsertId();
                $this->selectPerson($id);
                /*
                $dob_date = new DateTime($dob);
                $dob = $dob_date->format("Y-m-d");
                $dod_date = new DateTime($dod);
                $dod = $dod_date->format("Y-m-d");
                
                $this->data = [
                   'id_person' => $this->connection_object->lastInsertId(),
                   'name' => $name,
                   'surname' => $surname,
                   'dob' => $dob,
                   'place' => $place,
                   'dod' => $dod
               ];*/    
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    protected function updatePerson($name, $surname, $dob, $place, $dod) {
        try{
            //$data_input = "31/05/2012";
            $sql = "UPDATE people SET name='".$name."', "
                . "surname='".$surname."', "
                . "dob='" . STR_TO_DATE($dob, '%d/%m/%Y' )."', "
                . "place='".$place."' ";
                if ($dod <> ""){
                    $sql = $sql . ", dod='" . STR_TO_DATE($dod, '%d/%m/%Y' )."' ";
                }
                $sql = $sql . "WHERE id_person=".$this->data['id_person'];
            $stat = $this->connection_object->prepare($sql);
            $result = $stat->execute();
            if ($result) {
                $this->selectPerson($this->data['id_person']);
                /*$dob_date = new DateTime($dob);
                $dob = $dob_date->format("Y-m-d");
                $dod_date = new DateTime($dod);
                $dod = $dod_date->format("Y-m-d");
                
                $this->data['name'] = $name;
                $this->data['surname'] = $surname;  
                $this->data['dob'] = $dob; 
                $this->data['place'] = $place; 
                $this->data['dod'] = $dod; */
            }
            return $result;
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    public function isUnique($name, $surname, $dob){
        try{
            $result = false;
            $sql = "SELECT * FROM people WHERE name='".$name."' "
                . "AND surname='".$surname."' "
                . "AND dob='" . STR_TO_DATE($dob, '%d/%m/%Y' )."' ";
            if (isset($this->data['id_person'])){
                $sql = $sql . " AND id_person<>".$this->data['id_person'] ;
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
}