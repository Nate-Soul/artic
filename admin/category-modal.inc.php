<!-- edit category modal -->
<div class="modal fade" id="editCatModal<?php echo $category["id"]; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title"> Edit Category </h4>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?cat-key=<?php echo $category["id"]; ?>" id="editCategoryForm" method="POST">
                    <div class="form-group">
                        <label for="catNameEdit"> Name </label>
                        <input type="text" name="catNameEdit" id="catNameEdit" class="form-control" <?php Helper::stickyTextEdit($category["name"], "catNameEdit"); ?> required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="editCatForm">
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-success"> Update Category </button>
                </form>
                <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>


<!--delete category modal -->
<div class="modal fade" id="delCatModal<?php echo $category["id"]; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-danger"> Delete Category </h6>
                    <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?cat-key=<?php echo $category["id"]; ?>">
                    <p> 
                        Are you sure You want to delete this category 
                        <strong>category name</strong>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="sure" name="sure" class="btn btn-sm btn-danger">
                    </form>
                    <button class="btn btn-sm btn-warning" data-dismiss="modal"> Cancel </button>
                </div>
            </div>
        </div>
    </div>