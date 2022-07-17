<?php

class App extends Connect{

    private $table = "App";
    private $table_id = "id";
    private $app_dir  = array("../images/logo/", "../images/header/");
    

    public function getAppById($id = 1){
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}'";
        return $this->conn->fetchOne($sql);
    }

    public function getAppName(){
        $sql = "SELECT `name` FROM `{$this->table}`";
        $res = $this->conn->fetchOne($sql);
        $appName = $res['name'];
        return $appName;
    }

    public function getAppEmail(){
        $sql = "SELECT `email` FROM `{$this->table}`";
        $res = $this->conn->fetchOne($sql);
        $appEmail = $res["email"];
        return $appEmail;
    }


//create
    public function createApp($array){
        if(!empty($array)){
            $this->conn->prepareInsert($array);
            if($this->conn->insert($this->table)){
                return true;
            } else {
                return false;
            }
        }
    }

    //update
    public function updateApp($params, $id = 1){
        if(!empty($params) && !empty($id)){
            //do sth
            $this->conn->prepareUpdate($params);
            if($this->conn->update($this->table, $this->table_id, $id)){
                return true;
            }
            return false;
        }
    }


    //delete
    public function resetApp(){
        //select requested article
        $find_app = $this->getAppById();
        $id       = $find_app["id"];
        if($find_app){
            $delete = "DELETE FROM `{$this->table}` WHERE `id` = '{$id}'";
            $del_query = $this->conn->query($delete);
            if($del_query){
                //Helper::removeFile($this->post_dir, $find_app["app_logo"]);
                return true;                
            }
            return false;
        }
    }

    public function getAppCount(){
        $sql = "SELECT * FROM `{$this->table}`";
        return $this->conn->fetchRows($sql);
    }


}