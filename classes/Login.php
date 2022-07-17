<?php

session_start();
class Login extends Connect{

    public  $_errors = array();
    private $_table  = "admin";
    private $_login_path = "../2412157914.php";
    private $_admin_path = "admin/";
    private $_author_path = "author/";

    public function loginAdmin($login){
            $adminObj             = new Admins();
            $login_rows           = $adminObj->fetchUserByLogin($login);
            $_SESSION["user"]     = $login_rows;
        
            if($_SESSION["user"]["role"] == "Admin"){
            //redirect_to
            Helper::redirect_to($this->_admin_path);
            $_SESSION["msg"]["welc"] = "Welcome Back ".ucfirst($_SESSION["user"]["username"]);
            } else {
                Helper::redirect_to($this->_author_path);
                $_SESSION["msg"]["welc"] = "Welcome Back ".ucfirst($_SESSION["user"]["username"]);
            }
    }

    public static function isLoggedIn(){
        if(!isset($_SESSION["user"])){
            $_SESSION["msg"]["err"] = "You Must Login First";
            Helper::redirect_to("../2412157914.php");
        }
    }

    public static function isAuthor(){
        if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "Author"){
            return true;
        }
        return false;
    }

    public static function isAdmin(){
        if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "Admin"){
            return true;
        }
        return false;
    }

    //if errors are not empty throw errors;
    public function throwErrs(){
        if(isset($this->_errors) && !empty($this->_errors)){
            foreach ($this->_errors as $error){
                echo "<div class=\"alert alert-danger alert-dismissible\">
                <a role=\"button\" data-dismiss=\"alert\" class=\"close\">&times;</a>";
                echo $error;
                echo "</div>";
            }
        }

    }




}