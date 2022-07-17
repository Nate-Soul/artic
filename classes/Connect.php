<?php

class Connect{

    public $conn = null;

    public function __construct(){
        $this->conn = new Dbase();
    }

}