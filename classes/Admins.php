<?php

class Admins extends Connect{

    private $table     = "admin";
    private $table_id  = "admin_id";
    public $avatar_dir = "../images/admins/";

    public function fetchAdmins(){
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$this->table_id}`";
        return $this->conn->fetchAll($sql);
    }

    public function fetchAdminById($id){
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}'";
        return $this->conn->fetchAll($sql);
    }

    public function getAdminCount(){
        $sql = "SELECT * FROM `{$this->table}`";
        return $this->conn->fetchRows($sql);
    }

    public function checkIfMemeberAlreadyExist($username, $email){
        $sql = "SELECT * FROM `{$this->table}` WHERE `username` = '{$username}' OR `email` = '{$email}'";
        return $this->conn->fetchRows($sql);
    }

    public function fetchPassword($id){
        $sql = "SELECT `{$this->table_id}`, `password` FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}' ";
        $password = $this->conn->fetchOne($sql);
        return $password["password"];
    }

    public function fetchUserByLogin($login){
        $sql = "SELECT * FROM `{$this->table}` WHERE `email` = '{$login}' OR `username` = '{$login}'";
        return $this->conn->fetchOne($sql);
    }

    public function checkUserByLogin($login){
        $login = "SELECT * FROM `{$this->table}` WHERE `email` = '{$login}'  OR `username` = '{$login}'";
        return $this->conn->fetchRows($login);
    }

    public function addNewMember($array){
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
    public function updateMemberInfo($params = null, $id = null){
        if(!empty($params) && !empty($id)){
            //do sth
            $this->conn->prepareUpdate($params);
            if($this->conn->update($this->table, $this->table_id, $id)){
                return true;
            }
            return false;
        }
    }

    //update member password
    public function updateMemberPsw($params, $id){
        $update_psw = $this->updateMemberInfo($params, $id);
        if($update_psw){
            return true;
        }
        return false;       
    }


    //delete
    public function deleteMember($id){
        //select requested member
        $find_member = $this->fetchAdminById($id);
        //if member is found proceed to delete
        if($find_member){
            $delete = "DELETE FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}'";
            $del_query = $this->conn->query($delete);
            if($del_query){
               // Helper::removeFile($this->post_dir, $find_member["article_image"]);
                return true;                
            }
            return false;
        }
    }

    public function deleteMemberWithPosts($id){
        //select requested member
        $find_member = $this->fetchAdminById($id);
        //if member is found proceed to delete
        if($find_member){
            //delete member
            $delete = "DELETE FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}'";
            $del_query = $this->conn->query($delete);
            //delete member's post
            if($del_query){
                //delete profile picture
               // Helper::removeFile($this->post_dir, $find_member["article_image"]);
                return true;                
            }
            return false;
        }
    }


    public function searchMembers($q){
        $sql = "SELECT * FROM `{$this->table}` WHERE `username` LIKE '%$q%' OR `email` LIKE '%$q%'";
        return $this->conn->fetchAll($sql);
    }

}