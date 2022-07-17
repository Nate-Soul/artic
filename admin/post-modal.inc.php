 <!-- delete post modal-->
 <div class="modal fade" id="deletePostModal<?php echo $article["article_id"]; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger"> Delete Post </h6>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?post-key=<?php echo $article["article_id"]; ?>">
                <p> 
                    Are you sure You want to delete this post 
                    <strong><?php echo $article["article_subject"]; ?></strong>
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