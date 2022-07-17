<?php 
require("../core/autoload.php");
require("../core/authx.php");
$catObj   = new Category();
$formObj  = new Forms();
$page     = "Categories";
$errors   = array();
$messages = array();

if(isset($_POST["addCategoryForm"])){

    $catName = $formObj->validateField(Helper::sanitizeString($_POST["categoryName"], "string"));

    if(empty($catName)){
        $errors[] = "category name is required";
    }

    //check if category already exists
    $select_rows = $catObj->checkIfCategoryExist($catName);
    if($select_rows > 0){
        $errors[] = "This category already Exist";
    }

    if(empty($errors) === true){
        $params = array(
            "name"  => $catName
        );
        $inserted = $catObj->createCategory($params);
        if($inserted){
            $messages[] = "category added";
            Helper::refresh();
        } else {
            $errors[] = "category wasn't created succesfully!";
        }
    }


}

/********************************************************************
 **************************** update category ************************
 ****************************************************************** */
if(isset($_POST["editCatForm"]) && isset($_GET["cat-key"]) && !empty($_GET["cat-key"]) && is_numeric($_GET["cat-key"])){

    $id = $catObj->conn->esc_data((int)$_GET["cat-key"]);
    $category = Helper::sanitizeString($_POST["catNameEdit"], "string");
    //update params in database
    $params = array( 
        "name" => $category
    );
    if($catObj->updateCategory($params, $id)){
        $messages[] = "Category Updated!";
        Helper::refresh();
    } else {
        $errors[] = "Something went wrong... Couldn't Update Category";
    }
}

/********************************************************************
 **************************** delete category ***********************
 ****************************************************************** */
if(isset($_POST["sure"]) && !empty($_GET["cat-key"]) && is_numeric($_GET["cat-key"])){
    
    $id = (int)$_GET["cat-key"];
    $id = $catObj->conn->esc_data($id);
    if($catObj->deleteCategory($id)){
        $messages[] = "Category Deleted!";
        Helper::refresh();
    } else {
        $errors[] = "Oops! something went wrong, couldn't delete category";
    }
}


include "../templates/header.inc.php"; ?>
            <div class="col-md-3">
                <button data-target="#addCategoryModal" data-toggle="modal" class="btn btn-block mb-3 btn-success"> Add category </button>
            </div>
            <div class="col-md-6 col-sm-12 ml-auto">
                <form action="<?php Forms::post2self(); ?>" method="GET">
                    <div class="input-group">
                        <input type="search" name="s" id="searchCategories" placeholder="search categories" class="form-control"/>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-ente">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
        
<!--category-section-->
<section id="categories" class="py-4">
    <div class="container">
        <div class="row">
            <!-- search -->
            <div class="col-md-12 mb-4">
                <?php 
                if(isset($_GET["s"]) && !empty($_GET["s"])){
                $q = $catObj->conn->esc_data($_GET["s"]);
                $searchCats = $catObj->searchCategories($q);
                ?>
                <div class="card card-body">
                <?php if(!empty($searchCats)){ ?>
                    <p class="card-text text-success">
                        Showing Results for "<?php echo $q; ?>"
                    </p>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <thead class="thead-dark">
                                <th> <button class="btn btn-sm btn-ente"><span class="fa fa-times"></span></button> </th>
                                <th> Name </th>
                                <th> Modified </th>
                                <th colspan="2"> Action </th>
                            </thead>
                            <tbody>
                    <?php foreach($searchCats as $category){ ?>
                                <tr>
                                    <td><input type="checkbox" name="checkAll" class="check-one-all"></td>
                                    <td> <?php echo $category["name"]; ?> </td>
                                    <td> <?php echo Helper::dateFormater(4, $category["modified"]); ?> </td>
                                    <td> <a href="#editCatModal<?php echo $category["id"]; ?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a></td>
                                    <td><a href="#delCatModal<?php echo $category["id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                                </tr>
                                <?php include "category-modal.inc.php"; } unset($category); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                    <div class="alert alert-danger"> No results for this search </div>
                    <?php } ?>
                </div>
            <?php } ?>
            </div>
            <!--//search ends here-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">All Categories</h4>
                    </div>
                    <div class="card-body">
                    <?php
                        Helper::displayErrors($errors);
                        Helper::displayMsg($messages);
                    ?>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <th> <button class="btn btn-sm btn-ente"><span class="fa fa-times"></span></button> </th>
                                    <th> Name </th>
                                    <th> Modified </th>
                                    <th colspan="2"> Action </th>
                                </thead>
                                <tbody>
                                <?php 
                                    $pageObj = new Paging($catObj->getCategories(), 3);
                                    $categories = $pageObj->getRecords();
                                    foreach($categories as $category){
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="checkAll" class="check-one-all"></td>
                                        <td> <?php echo $category["name"]; ?> </td>
                                        <td> <?php echo Helper::dateFormater(4, $category["modified"]); ?> </td>
                                        <td> <a href="#editCatModal<?php echo $category["id"]; ?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a></td>
                                        <td><a href="#delCatModal<?php echo $category["id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                                    </tr>
                                    <?php include "category-modal.inc.php"; } unset($category); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?php echo $pageObj->getPaging(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../templates/footer.inc.php"; ?>

    <!-- add category modal -->
    <div class="modal fade" id="addCategoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title"> Add Category </h4>
                    <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
                </div>
                <div class="modal-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="addCategoryForm" method="POST">
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" name="categoryName" id="categoryName" placeholder="Category name" class="form-control" <?php Helper::stickyText("categoryName"); ?>/>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="addCategoryForm">
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-success"> Add Category </button>
                    </form>
                    <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
                </div>
            </div>
        </div>
    </div>
    
<?php include "../templates/scripts.inc.php"; ?>