<?php

class Articles extends Connect{

    private $table    = "articles";
    private $table_id = "article_id";
    public  $post_dir = "../images/articles/";

    //fetch all articles
    public function fetchArticles(){
        $post = "SELECT * FROM `{$this->table}` ORDER BY `article_date` DESC";
        return $this->conn->fetchAll($post);
    }

    //get recent articles
    public function getRecentArticles($num){
        $post = "SELECT * FROM `{$this->table}` ORDER BY `article_date` DESC LIMIT $num";
        return $this->conn->fetchAll($post);
    }

    //get recent articles by author
    public function getMyRecentArticles($author_id, $num = 5){
        $post = "SELECT * FROM `{$this->table}` WHERE `author_id` = '{$author_id}' ORDER BY `article_date` DESC LIMIT $num";
        return $this->conn->fetchAll($post);
    }

    //fetch article by id
    public function fetchArticleById($id){
        $sql = "SELECT * FROM `{$this->table}` WHERE `article_id` = '{$id}'";
        return $this->conn->fetchOne($sql);
    }

    //get all articles by category
    public function getArticlesByCat($cat){
        $sql = "SELECT * FROM `{$this->table}` WHERE `article_category` = '{$cat}'";
        return $this->conn->fetchAll($sql);
    }

    //get all articles by author case:ID
    public function getArticlesByAuthor($author_id){
        $sql = "SELECT * FROM `{$this->table}` WHERE `author_id` = '{$author_id}'";
        return $this->conn->fetchAll($sql);
    }
    
    //check if article already exists
    public function checkIfArticleExist($subject, $image){
        $select = "SELECT * FROM `{$this->table}` WHERE `article_subject` = '{$subject}' OR `article_image` = '{$image}'";
        return $this->conn->fetchRows($select);
    }

    //create new article
    public function createArticle($array){
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
    public function updateArticle($params = null, $id = null){
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
    public function deleteArticle($id){
        //select requested article
        $find_article = $this->fetchArticleById($id);
        if($find_article){
            $delete = "DELETE FROM `{$this->table}` WHERE `article_id` = '{$id}'";
            $del_query = $this->conn->query($delete);
            if($del_query){
                Helper::removeFile($this->post_dir, $find_article["article_image"]);
                return true;                
            }
            return false;
        }
    }

    //get total number of articles
    public function getArticleCount(){
        $sql = "SELECT * FROM `{$this->table}`";
        return $this->conn->fetchRows($sql);
    }

    //search articles
    public function searchArticles($q){
        $sql = "SELECT * FROM `{$this->table}` WHERE `article_subject` LIKE '%$q%' OR `article_body` LIKE '%$q%' OR `article_category` LIKE '%$q%'";
        return $this->conn->fetchAll($sql);
    }

    public function searchMyArticles($author_id, $q){
        $sql = "SELECT * FROM `{$this->table}` WHERE `author_id` = '{$author_id}' AND `article_subject` LIKE '%$q%' OR `article_body` LIKE '%$q%' OR `article_category` LIKE '%$q%'";
        return $this->conn->fetchAll($sql);
    }

    
    
}
