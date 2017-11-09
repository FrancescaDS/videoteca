<?php

class Mydb{
    public $mysql;
    
    public function __construct()
    {
        try
        {
            $dsn = 'mysql:host=localhost;dbname=videoteca';
            $this->mysql = new PDO($dsn, 'root', '');
        }
            catch (PDOException $e)
        {
            $this->mysql = null;
            return;
        }
    }
}

