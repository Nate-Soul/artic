<?php
require("../core/autoload.php");
require("../core/authx.php");
$page     = "Your Content";

$postsObj = new Articles();
$catObj   = new Category();
$adminObj = new Admins();
$tagsObj  = new Tags();

include "../templates/header.inc.php"; ?>
            <div class="col-md-6 mx-auto">
                <?php if(isset($_SESSION["msg"]["welc"])){ ?>
                    <div class="alert alert-info text-center">
                        <?php echo $_SESSION["msg"]["welc"]; 
                        unset($_SESSION["msg"]["welc"]);
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
        
<!--recent-section-->
<section id="recentPost" class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Recent Posts </h4>
                    </div>
                    <div class="card-body">
                    <?php if($postsObj->getArticleCount() > 0){ ?>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <th> Title </th>
                                    <th> Categories </th>
                                    <th> Posted </th>
                                    <th> Details </th>
                                </thead>
                                <tbody>
                                <?php $recentPosts = $postsObj->getRecentArticles(5);
                                foreach($recentPosts as $recentPost){ ?>
                                    <tr>
                                        <td> <?php echo $recentPost["article_subject"]; ?> </td>
                                        <td> <?php echo $recentPost["article_category"]; ?> </td>
                                        <td> <?php echo $recentPost["article_date"]; ?> </td>
                                        <td><a href="details.php?post-key=<?php echo $recentPost["article_id"]; ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View </a> </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-body bg-warning text-center mb-3">
                    <h4 class="card-title"> Posts </h4>
                    <p class="card-text display-4"><i class="fa fa-pencil"></i> <?php echo $postsObj->getArticleCount(); ?> </p>
                    <a href="posts.php" class="btn btn-sm btn-outline-light">view</a>
                </div>
                <div class="card card-body bg-success text-light text-center mb-3">
                    <h4 class="card-title"> Categories </h4>
                    <p class="card-text display-4"><i class="fa fa-folder"></i> <?php echo $catObj->getCategoryCount(); ?> </p>
                    <a href="categories.php" class="btn btn-sm btn-outline-light">view</a>
                </div>
                <div class="card card-body bg-secondary text-light text-center mb-3">
                    <h4 class="card-title"> Authors </h4>
                    <p class="card-text display-4"><i class="fa fa-users"></i> <?php echo $adminObj->getAdminCount(); ?> </p>
                    <a href="members.php" class="btn btn-sm btn-outline-light">view</a>
                </div>
                <div class="card card-body bg-info text-light text-center mb-3">
                    <h4 class="card-title"> Tags </h4>
                    <p class="card-text display-4"><i class="fa fa-tags"></i> <?php //echo $postsObj->getArticleCount(); ?> </p>
                    <a href="#" class="btn btn-sm btn-outline-light">Manage</a>
                </div>
                <div class="card card-body bg-busi text-light text-center mb-3">
                    <h4 class="card-title"> Comments </h4>
                    <p class="card-text display-4"><i class="fa fa-comment"></i> <?php //echo $postsObj->getArticleCount(); ?> </p>
                    <a href="#" class="btn btn-sm btn-outline-light">Manage</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../templates/footer.inc.php"; ?>    
<?php include "../templates/scripts.inc.php"; ?>