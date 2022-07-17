<?php

class Category extends Connect{

    private $table = "categories";
    private $table_id = "id";
    private $menu;

    public function getCategories(){
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `id` DESC";
        return $this->conn->fetchAll($sql);
    }


    public function getCategoryById($id){
        $sql = "SELECT * FROM `{$this->table}` WHERE `id` = '{$id}'";
        return $this->conn->fetchOne($sql);
    }

    public function checkIfCategoryExist($cat_name){
        $sql = "SELECT `name` FROM `{$this->table}` WHERE `name` = '{$cat_name}'";
        return $this->conn->fetchRows($sql);
    }

    //create
    public function createCategory($array){
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
    public function updateCategory($params = null, $id = null){
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
    public function deleteCategory($id){
        //select requested Category
        $find_Category = $this->getCategoryById($id);
        if($find_Category){
            $delete = "DELETE FROM `{$this->table}` WHERE `{$this->table_id}` = '{$id}'";
            $del_query = $this->conn->query($delete);
            if($del_query){
                return true;                
            }
            return false;
        }
    }


    public function getCategoryCount(){
        $sql = "SELECT * FROM `{$this->table}`";
        return $this->conn->fetchRows($sql);
    }

    public function getCategoryMenu(){
        foreach ($this->getCategories() as $catMenu){
            echo "<a href=\"./category.php?category=".$catMenu["name"]."\" class=\"dropdown-item\">".$catMenu["name"]."</a>";
        }
    }


    public function searchCategories($q){
        $sql = "SELECT * FROM `{$this->table}` WHERE `name` LIKE '%$q%'";
        return $this->conn->fetchAll($sql);
    }






}