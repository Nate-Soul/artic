<?php
require("../core/autoload.php");
require("../core/authx.php");

$articleObj     = new Articles();
$catObj         = new Category();
$formObj        = new Forms();
$errors         = array();
$messages       = array();
$page           = "posts";
$post_dir       = $articleObj->post_dir;
$post_author    = $_SESSION["user"]["username"];
$post_author_id = $_SESSION["user"]["admin_id"];



if(isset($_POST["createNewArticle"])){
    $image     = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $imagetype = $_FILES["image"]["type"];
    $imagesize = $_FILES["image"]["size"];
    $maxsize   = 1024 * 1024 * 5;
    $imageerror= $_FILES["image"]["error"];

    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
    $ext     = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    if(!array_key_exists($ext, $allowed) || !in_array($imagetype, $allowed)){
        $errors[] = 'Please Select A Valid Image Format';
    }
    if($imagesize > $maxsize){
        $errors[] = 'Image Size Cannot Exceed 5MB';
    }
    if(file_exists($post_dir.$image)){
        $errors[] = 'Image already exists';
    }

    $subject   = $formObj->validateField(Helper::sanitizeString($_POST["title"], "string"));
    $body      = $formObj->validateField($_POST["body"]);
    $category  = $formObj->validateField(Helper::sanitizeString($_POST["category"], "string"));
    $author    = $formObj->validateField(Helper::sanitizeString($post_author, "string"));
    $author_id = Helper::sanitizeString($post_author_id, "int");
    
    if(empty($image) || empty($category) || empty($subject) || empty($body)){
        $errors[] = "Fields Are Empty, Fill The Form To Continue";
    }

    //check if article already exists
    $select_rows = $articleObj->checkIfArticleExist($subject, $image);
    if($select_rows > 0){
        $errors[] = "This Article already Exist";
    }

    if(empty($errors) === true){
        $params = array(
            "article_subject"  => $subject,
            "article_body"     => $body,
            "article_category" => $category,
            "article_image"    => $image, 
            "article_author"   => $author, 
            "author_id"        => $author_id
        );
        $inserted = $articleObj->createArticle($params);
        if($inserted && move_uploaded_file($image_tmp, $post_dir.$image)){
            $messages[] = "Article posted";
            Helper::refresh();
        } else {
            $errors[] = "article wasn't created succesfully!";
            Helper::refresh();
        }
    }


}

/***********************************
 *********** delete post **********
 ********************************* */
if(isset($_POST["sure"]) && !empty($_GET["post-key"]) && is_numeric($_GET["post-key"])){
    
    $id = (int)$_GET["post-key"];
    $id = $articleObj->conn->esc_data($id);
    if($articleObj->deleteArticle($id)){
        $messages[] = "Article Was Deleted Successfully";
        header("refresh:2 posts.php");
    } else {
        $errors[] = "Oops! something went wrong, couldn't delete post";
    }
}

 include "../templates/header.inc.php"; ?>
            <div class="col-md-3">
                <button data-target="#addPostModal" data-toggle="modal" class="btn btn-block mb-3 btn-warning"> Add Post </button>                
            </div>
            <div class="col-md-6 ml-auto">
                <form action="<?php Forms::post2self(); ?>" method="GET">
                    <div class="input-group">
                        <input type="search" name="s" id="searchPosts" placeholder="search posts" class="form-control"/>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-ente">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
        
<!--post-section-->
<section id="posts" class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                <?php 
                if(isset($_GET["s"]) && !empty($_GET["s"])){
                $q = $articleObj->conn->esc_data($_GET["s"]);
                $searchArticles = $articleObj->searchArticles($q);
                ?>
                    <div class="card card-body">
                    <?php if(!empty($searchArticles)){ ?>
                        <p class="card-text text-success">
                            Showing Results for "<?php echo $q; ?>"
                        </p>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <thead class="thead-dark">
                                    <th> <button class="btn btn-sm btn-ente" id="checkAll"><span class="fa fa-times"></span></button> </th>
                                    <th> Title </th>
                                    <th> Category </th>
                                    <th> Date </th>
                                    <th> Body </th>
                                    <th> Image </th>
                                    <th colspan="2"> Action </th>
                                </thead>
                                <tbody>
                        <?php foreach($searchArticles as $article){ ?>
                            <tr>
                                <td><input type="checkbox" name="check_id[]" class="check-one-all" value="<?= $article["article_id"]; ?>"> </td>
                                <td> <?php echo $article["article_subject"]; ?> </td>
                                <td> <?php echo $article["article_category"]; ?> </td>
                                <td> <?php echo Helper::dateFormater(4, $article["article_date"]); ?> </td>
                                <td> <?php echo Helper::shortenString($article["article_body"], 100); ?> </td>
                                <td><img src="<?php echo $post_dir.$article["article_image"]; ?>" height="45" width="45" alt="<?php echo $article["article_subject"]; ?>"/></td>
                                <td><a href="details.php?post-key=<?php echo $article["article_id"]; ?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit </a> </td>
                                <td><a href="#deletePostModal<?php echo $article["article_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                            </tr>
                        <?php include "post-modal.inc.php"; } unset($article); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-danger"> No results for this search </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Posts</h4>
                        </div>
                        <div class="card-body">
                        <?php Helper::displayErrors($errors);
                              Helper::displayMsg($messages); 
                            if($articleObj->getArticleCount() > 0){ ?>
                            <div class="table-responsive-sm">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <th> <button class="btn btn-sm btn-ente" id="checkAll"><span class="fa fa-times"></span></button> </th>
                                        <th> Title </th>
                                        <th> Category </th>
                                        <th> Date </th>
                                        <th> Body </th>
                                        <th> Image </th>
                                        <th colspan="2"> Action </th>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $pageObj  = new Paging($articleObj->fetchArticles());
                                        $articles = $pageObj->getRecords();
                                        foreach($articles as $article){ ?>
                                        <tr>
                                            <td><input type="checkbox" name="check_id[]" class="check-one-all" value="<?= $article["article_id"]; ?>"> </td>
                                            <td> <?= $article["article_subject"]; ?> </td>
                                            <td> <?= $article["article_category"]; ?> </td>
                                            <td> <?= Helper::dateFormater(4, $article["article_date"]); ?> </td>
                                            <td> <?= Helper::decodeHtml(Helper::shortenString($article["article_body"], 100)); ?> </td>
                                            <td><img src="<?= $post_dir.$article["article_image"]; ?>" height="45" width="45" alt="<?= $article["article_subject"]; ?>"/></td>
                                            <td><a href="details.php?post-key=<?= $article["article_id"]; ?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit </a> </td>
                                            <td><a href="#deletePostModal<?= $article["article_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                                        </tr>
                                        <?php include "post-modal.inc.php"; } unset($article); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else { ?>
                            <div class="alert alert-info">Start populating data to display post here...</div>
                            <?php } ?>
                        </div>
                        <div class="card-footer">
                            <?php if($articleObj->getArticleCount() > 0){ echo $pageObj->getPaging(); } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "../templates/footer.inc.php"; ?>

    <!-- addpost modal -->
    <div class="modal fade" id="addPostModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h4 class="modal-title"> Add Post </h4>
                    <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
                </div>
                <div class="modal-body">
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="addpostForm" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label for="title">Post Title</label>
                            <input type="text" name="title" id="title" placeholder="Post Title" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="category" class="custom-input-label"> Select a category</label>
                            <select name="category" id="category" class="custom-select">
                                <optgroup>
                                <?php 
                                $catmenus = $catObj->getCategories();
                                foreach($catmenus as $catmenu){ ?>
                                <option value="<?= strtolower($catmenu["name"]); ?>" <?php Helper::stickySelect("category", $catmenu["name"]); ?>><?= ucfirst($catmenu["name"]); ?></option>
                                <?php } unset($catmenu); ?>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group custom-file mb-3">
                            <label for="image" class="custom-file-label"> Post Image
                            <input type="file" name="image" id="image" class="custom-file-input"/>
                            </label>
                            <input type="hidden" name="createNewArticle">
                        </div>
                        <div class="form-group">
                            <label for="body"> Post Body</label>
                            <textarea name="body" id="body" placeholder="Post Body" class="form-control ckeditor"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-warning"> Save Post</button>
                    </form>
                    <button class="btn btn-danger" data-dismiss="modal"> cancel </button>
                </div>
            </div>
        </div>
    </div>
    
<?php include "../templates/scripts.inc.php"; ?>