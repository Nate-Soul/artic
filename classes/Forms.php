<?php

class Forms extends Connect{

    private $expected;
    private $required;
    private $validated;

    public function isPost($field){
        if(isset($_POST[$field])){
            return true;
        }
        return false;
    }


    public function checkIfEmpty($array){
        foreach($array as $single){
            if(isset($single) && !empty($single)){
                return true;
            }
            return false;
        }
    }

    public static function post2self(){
        return htmlspecialchars($_SERVER["PHP_SELF"]);
    }

    public function validateField($field){
        if(!empty($field)){
            $field = $this->conn->validate($field);
        }
        return $field;
    }


    public function isEmail($email){
        if(!empty($email)){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return false;
            }
            return true;
        }
    }



}