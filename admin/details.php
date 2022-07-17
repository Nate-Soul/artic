<?php 
require("../core/autoload.php");
require("../core/authx.php");
$page        = "Post Edit";
$articleObj  = new Articles();
$formObj     = new Forms();
$errors      = array();
$messages    = array();
$article_dir = $articleObj->post_dir;

if(isset($_GET["post-key"]) && !empty($_GET["post-key"]) && is_numeric($_GET["post-key"])){
    $id          = (int)$_GET["post-key"];
    $single_post = $articleObj->fetchArticleById($id);
} else {
    $single_post = null;
    Helper::refresh(1, "posts.php");
}

if(isset($_POST["updateArticleForm"])){

    $id = (int)$_POST["post-identifier"];
	
	//init and validate variables    
    $old_image = $_POST["oldPostImage"];
    $image_tmp = $_FILES["postImage"]["tmp_name"];
    $image     = $_FILES["postImage"]["name"];		

    $subject  = $formObj->validateField(Helper::sanitizeString($_POST["postTitle"], "string"));    
    $body     = $formObj->validateField($_POST["postBody"]);
    $category = $formObj->validateField(Helper::sanitizeString($_POST["postCategory"], "string"));
    
    if(empty($image)){
        $postImage = $old_image;
    } else {
        $postImage = $image;
    }

    //update params in database
    $params = array( 
        "article_body"     => $body,
        "article_subject"  => $subject,
        "article_category" => $category,
        "article_image"    => $postImage
    );
    if($articleObj->updateArticle($params, $id)){
        if(move_uploaded_file($image_tmp, $article_dir.$image)){
            Helper::removeFile($article_dir, $old_image);
        }
        $messages[] = "Article Updated";
    }else {
        $errors[] = "Something went wrong... Couldn't update Article";
    }
}

/***********************************
 *********** delete post **********
 ********************************* */
if(isset($_POST["sure"]) && !empty($_GET["post-key"]) && is_numeric($_GET["post-key"])){
    
    $id = (int)$_GET["post-key"];
    $id = $articleObj->conn->esc_data($id);
    if($articleObj->deleteArticle($id)){
        $messages[] = "Article Deleted";
        Helper::refresh(1, "posts.php");
    } else {
        $errors[] = "Oops! something went wrong, couldn't delete post";
    }
}

include "../templates/header.inc.php"; ?>
            <div class="col-md-4">
                <a href="index.php" class="btn btn-block mb-3 btn-warning"> Go to Dashboard</a>
            </div>
            <div class="col-md-4">
                <a href="javascript:history.go(-1)" class="btn btn-block mb-3 btn-info"> <i class="fa fa-chevron-left"></i> Previous page </a>
            </div>
        </div>
    </div>
</section>
        
<!--Edit Post Section-->
<section id="editPost" class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> Edit Post One </h4>
                        </div>
                        <div class="card-body">
                        <?php   
                            Helper::displayErrors($errors);
                            Helper::displayMsg($messages); 
                        ?>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="postTitle"> Post Title </label>
                                    <input type="text" name="postTitle" id="postTitle" <?php echo Helper::stickyTextEdit($single_post["article_subject"], "postTitle"); ?> class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label for="postCategory" class="custom-input-label">Post Category </label>
                                    <select name="postCategory" id="postCategory" class="custom-select">
                                        <optgroup>
                                            <option value="technology" <?php Helper::stickySelectEdit("postCategory", $single_post["article_category"], "technology"); ?>>Technology</option>
                                            <option value="fashion" <?php Helper::stickySelectEdit("postCategory", $single_post["article_category"], "fashion"); ?>>Fashion</option>
                                            <option value="politics" <?php Helper::stickySelectEdit("postCategory", $single_post["article_category"], "politics"); ?>>Politics</option>
                                            <option value="business" <?php Helper::stickySelectEdit("postCategory", $single_post["article_category"], "business"); ?>>Business</option>
                                            <option value="entertainment" <?php Helper::stickySelectEdit("postCategory", $single_post["article_category"], "entertainment"); ?>>Entertainment</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group custom-file">
                                    <label for="postImage" class="custom-file-label"> update featured blog post image </label>
                                    <input type="file" name="postImage" id="postImage" class="custom-file-input"/>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="updateArticleForm">
                                    <input type="hidden" name="post-identifier" value="<?php echo $single_post["article_id"]; ?>">
                                    <input type="hidden" name="oldPostImage" value="<?php echo $single_post["article_image"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="postBody"> Post Body </label>
                                    <textarea name="postBody" id="postBody" class="form-control ckeditor" cols="30" rows="5"><?php Helper::stickyAreaEdit($single_post["article_body"], "postEdit") ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block"> Update post </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- post preview -->
                <div class="col-md-4">
                    <div class="card text-center p-2">
                        <div class="card-header">
                            <h6 class="card-title">Post preview</h6>
                        </div>
                        <img src="<?php echo $article_dir.$single_post["article_image"]; ?>" alt="<?php echo $single_post["article_subject"]; ?>" class="img-fluid card-img-top" />
                        <h4 class="my-2"> <?php echo $single_post["article_subject"]; ?> </h4>
                        <p><strong> Category: </strong><span class="label label-sm bg-<?php echo substr($single_post["article_category"], 0, 4); ?>"> <?php echo $single_post["article_category"]; ?> </span></p>
                        <small class="text-muted"> Written On <?php echo $single_post["article_date"]; ?> By <?php echo $single_post["article_author"]; ?> </small>
                        <p class="card-text text-justify"><?php echo Helper::decodeHtml($single_post["article_body"]); ?></p>
                        <a href="#deletePostModal" class="btn btn-sm btn-ente" data-toggle="modal"><i class="fa fa-trash"></i> Delete Post</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    
<?php include "../templates/footer.inc.php"; ?>    

    <!-- delete post modal-->
    <div class="modal fade" id="deletePostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-danger"> Delete Post </h6>
                    <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?post-key=<?php echo $single_post["article_id"]; ?>">
                    <p> 
                        Are you sure You want to delete this post 
                        <strong><?php echo $single_post["article_subject"]; ?></strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="sure" name="sure" class="btn btn-sm btn-danger">
                    </form>
                    <button href="#" class="btn btn-sm btn-warning" data-dismiss="modal"> Cancel </button>
                </div>
            </div>
        </div>
    </div>

<?php include "../templates/scripts.inc.php"; ?>