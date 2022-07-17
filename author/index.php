<?php
require("../core/autoload.php");
require("../core/auth.php");
$page     = "Your Content";

$postsObj = new Articles();
$authorId = $_SESSION["user"]["admin_id"];
$myarticles = $postsObj->getMyRecentArticles($authorId);
$catObj     = new Category();
$adminObj = new Admins();
$tagsObj  = new Tags();

include "../templates/author-header.inc.php"; ?>
            <div class="col-md-3">
                <?php if(isset($_SESSION["msg"]["welc"])){ ?>
                    <div class="alert alert-info">
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
                    <?php if(count($myarticles) > 0){ ?>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <th> Title </th>
                                    <th> Categories </th>
                                    <th> Posted </th>
                                    <th> Details </th>
                                </thead>
                                <tbody>
                                <?php foreach($myarticles as $recentPost){ ?>
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
                    <h4 class="card-title"> My Posts </h4>
                    <p class="card-text display-4"><i class="fa fa-pencil"></i> <?php echo count($myarticles); ?> </p>
                    <a href="posts.php" class="btn btn-sm btn-outline-light">view</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../templates/footer.inc.php"; ?>    
<?php include "../templates/scripts.inc.php"; ?>