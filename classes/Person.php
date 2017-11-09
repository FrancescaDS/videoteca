<?php

class Person{
    //data[] = array where keys are names of fields
    //id_person, name, surname
    protected $data = [];
    
    protected $connection_object;
    
    public function __construct($id = 0) {
        try {
            if ($id != 0){
                $this->selectPerson($id);
            }
        } catch (Exception $ex) {
            exit($ex->getMessage());
        }
    }
    
    private function selectPerson($id){
        try {
            $db = new Mydb();
            $this->connection_object = $db->mysql;
            
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
    
}