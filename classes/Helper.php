<?php


class Helper{


    //shorten string
    public static function shortenString($string, $len = 200) {
		if (strlen($string) > $len) {
			$string = trim(substr($string, 0, $len));
			$string = substr($string, 0, strrpos($string, " "))."&hellip;";
		} else {
			$string .= "&hellip;";
		}
		return $string;
    }

    //shorten string alternative
    public static function shortenStringAlt($string, $length = 4){
        if(strlen($string) > $length){
            $string = trim(substr($string, 0, $length));
        }
        return $string;
    }


    //redirect
    public static function redirect_to($location){
        return header("Location: ".$location);
    }


    //refresh
    public static function refresh($case = null, $url = null){
        switch($case){
            case 1:
            return header("refresh:2 ".$url);
            break;
            default:
            return header("refresh:2 ".$_SERVER["PHP_SELF"]);
            break;
        }
    }

    //error message handler
    public static function displayErrors($errors){
        if(isset($errors) && !empty($errors) === true){
            foreach($errors as $err){
                echo "<div class=\"alert alert-danger alert-dismissible\">
                <a role=\"button\" class=\"close\" data-dismiss=\"alert\"> &times </a>";
                echo $err;
                echo "</div>";
            }
        }
    }


    //success message handler
    public static function displayMsg($messages){
        if(isset($messages) && !empty($messages) === true){
            foreach($messages as $msg){
                echo "<div class=\"alert alert-success\">";
                echo $msg;
                echo "</div>";
            }
        }
    }


    public static function dateFormater($case = null, $timestamp = null){

        $time = empty($timestamp) ? time() : strtotime($timestamp);
		
		switch($case) {
			case 1:
			// 01/01/2010
			return date('d/m/Y', $time);
			break;
			case 2:
			// Monday, 1st January 2010, 09:30:56
			return date('l, jS F Y, H:i:s', $time);
			break;
			case 3:
			// 2010-01-01-09-30-56
			return date('Y-m-d-H-i-s', $time);
            break;
            case 4:
            //mon, 12th july, 2020
            return date("D d M, Y", $time);
            break;
			default:
			return date('Y-m-d H:i:s', $time);
		}

    }

    //sanitizer
    public static function sanitizeString($string, $type){
        switch($type){
            case "string":
            $string = filter_var($string, FILTER_SANITIZE_STRING);
            break;
            case "email":
            $string = filter_var($string, FILTER_SANITIZE_EMAIL);
            break;
            case "url":
            $string = filter_var($string, FILTER_SANITIZE_URL);
            break;
            case "int":
            $string = filter_var($string, FILTER_SANITIZE_NUMBER_INT);
            break;
            case "float":
            $string = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT);
            break;
        }
        return $string;
    }

    //upload file
    public static function uploadFile($path, $img_tmp, $img){
        if(move_uploaded_file($img_tmp, $path.$img)){
            return true;
        }
        return false;
    }

    //remove file
    public static function removeFile($path, $oldImg){
        $oldi = scandir($path);
        unset($oldi[0], $oldi[1]);
        if(file_exists($path.$oldImg)){
            unlink($path.$oldImg);
        }
    }

    //sticky input fields
    public static function stickyText($field){
        if(isset($_POST[$field])){
            echo "value=\"".strip_tags($_POST[$field])."\"";
        }
    }

    //sticky input fields
    public static function stickyTextAlt($field){
        if(isset($_POST[$field])){
            echo "value=\"".$_POST[$field]."\"";
        }
    }

    //sticky input for edits fields
    public static function stickyTextEdit($val, $field){
        if(isset($_POST[$field]) && !empty($_POST[$field])){
            echo "value=\"".strip_tags($_POST[$field])."\"";
        } elseif(!empty($val)){
            echo "value=\"".strip_tags($val)."\"";
        }
    }

    //stick text area fields
    public static function stickyArea($field){
        if(isset($_POST[$field])){
            echo strip_tags($_POST[$field]);
        }        
    }

    //stick text area for edits fields
    public static function stickyAreaEdit($val, $field){
        if(isset($_POST[$field]) && !empty($_POST[$field])){
            echo strip_tags($_POST[$field]);
        } elseif(!empty($val)){
            echo strip_tags(self::decodeHtml($val));
        }        
    }

    //sticky select
    public static function stickySelect($field, $value){
        if(isset($_POST[$field]) && $_POST[$field] == $value){
            echo "selected=\"selected\"";
        }
    }

    //sticky select for edit fields
    public static function stickySelectEdit($field, $value, $newval){
        if(!empty($value) && $value == $newval || isset($_POST[$field]) && !empty($_POST[$field]) && $_POST[$field] == $newval){
            echo "selected=\"selected\"";
        }
    }

    public static function decodeHtml($string) {
        return htmlspecialchars_decode(html_entity_decode($string));
    }

    public static function genPassword(){
        return substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
    }





}