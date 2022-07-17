<?php
//session_start();
if(Login::isAuthor() === false){
    $_SESSION["msg"]["err"] = "You Must Login First";
    exit(Helper::redirect_to("../2412157914.php"));
}
?>