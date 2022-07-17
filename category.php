<?php 
    require('core/autoload.php');
    $appObj = new App();
    $articleObj = new Articles();
    $catObj = new Category();
    $formObj = new Forms();

if(isset($_GET["category"]) && !empty($_GET["category"])){
    $catQuery      = $formObj->validateField($_GET["category"]);
    $catResults    = $articleObj->getArticlesByCat($catQuery);
} else {
    Helper::redirect_to("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--links-->
    <link rel="stylesheet" href="vendor/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="vendor/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all">
    <title>Ptv | Admin Login </title>
</head>
<body>
        <nav id="menu" class="navbar navbar-expand-sm navbar-light bg-light">
                <div class="container">
                    <!--navbar brand-->
                    <a href="index.php" class="navbar-brand"><img src="images/logo/logo.png" alt="logo"/></a>
                    <button class="navbar-toggler" data-toggle="collapse" data-target="#menuCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!--collapsible-->
                    <div class="collapse navbar-collapse" id="menuCollapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item px-2">
                                <a class="nav-link" href="index.php"> Home </a>
                            </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> Categories </a>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item"> Sports </a>                            
                                    <a href="#" class="dropdown-item"> Technology </a>                            
                                    <a href="#" class="dropdown-item"> Politics </a>                            
                                    <a href="#" class="dropdown-item"> Business </a>                            
                                    <a href="#" class="dropdown-item"> Entertainment </a>                            
                                    <a href="#" class="dropdown-item"> Fashion </a>                          
                                    <a href="#" class="dropdown-item"> Lifestyle </a>                        
                                 </div>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link" href="About.php"> About </a>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link" href="contact.php"> Contact </a>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link current" href="admin/index.php"> My Account </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!--header-->
            <header class="py-3 fancyHeader">
                <div class="container">
                    <div class="row">
                        <div class="d-block mx-auto col-md-6 text-center py-4">
                            <h1 class="display-4"> Sports </h1>
                        </div>
                    </div>
                </div>
            </header>
        

<!--post section-->
<section id="posts" class="py-5">
        <div class="container">
            <div class="row">               
                 <main class="col-lg-9 col-md-9 col-sm-12 mb-4">
            <!-- article -->
            <?php if(count($catResults) > 0){
                //$pageObj  = new Paging($articleObj->fetchArticles());
                //$articles = $pageObj->getRecords();
                foreach($catResults as $article){ ?>
                <article class="blog-post py-3">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?= "images/articles/".$article["article_image"]; ?>" class="img-fluid" alt="Post Image"/>
                        </div>
                        <div class="col-md-8">
                            <div class="post-header">
                                <h4 class="post-title d-block mb-4 text-center"> <?= $article["article_subject"]; ?> </h4>
                                <p class="post-ref small">
                                    Written By <?= ucfirst($article["article_author"]); ?> On <?= Helper::dateFormater(4, $article["article_date"]); ?> <span class="label bg-<?= Helper::shortenStringAlt($article["article_category"]); ?> text-light p-1"> <?= $article["article_category"]; ?> </span>
                                </p>
                            </div>
                            <div class="post-body text-justify">
                                <?= Helper::shortenString(Helper::decodeHtml($article["article_body"])); ?>
                            </div>
                            <p class="text-right">
                                <a href="single_post.php?article=<?= $article["article_id"]; ?>" class="post-link"> Read more &raquo;</a>
                            </p>
                        </div>
                    </div>
                    <div class="divider"></div>
                </article>
                <?php } } else { ?>
                <div class="alert alert-warning">Category has been moved renamed or deleted</div>
                <?php } ?>
				<!-- article ends here -->
                     <!-- <nav class="nav-pills py-2">
                         <div class="pagination-wrapper row">
                             <div class="col-md-12">
                             </div>
                         </div>
                     </nav> -->
                 </main>
                 <aside id="side-content" class="col-lg-3 col-md-3 col-sm-12 mx-auto">
                     <form action="search.php" class="mb-5">
                            <div class="input-group">
                                <input type="search" name="q" id="q" class="form-control" placeholder="Search Post..."/>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-ente">
                                        <span class="fa fa-search"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <div class="my-4 text-left fancyCategory">
                        <h5 class="prim-color"> CATEGORIES </h5>
                        <ul>
                            <li><a href="category.php"> Sports </a></li>
                            <li><a href="category.php"> Politics </a></li>
                            <li><a href="category.php"> Fashion </a></li>
                            <li><a href="category.php"> Technology </a></li>
                            <li><a href="category.php"> Entertainment </a></li>
                            <li><a href="category.php"> Cars </a></li>
                        </ul>
                    </div>
                    <div class="my-4">
                        <h5 class="prim-color"> Recent Posts </h5>

                    </div>
                    <div class="my-4">
                        <h5 class="prim-color"> Popular Tags </h5>
                    </div>
                    <!--adverts-->    
                     <div class="my-4 text-center">
                         <small class="text-muted"> Advertisement </small>
                         <img src="images/header_2.jpeg" class="img-fluid img-thumbnail" alt="">
                     </div>
                     <div class="my-4 text-center">
                         <small class="text-danger">Advertisement</small>
                         <a href="#">
                             <img src="images/header_2.jpeg" class="img-fluid img-thumbnail" alt="">
                         </a>
                     </div>
                 </aside>
            </div>
        </div>
    </section>


    <!--footer-->
    <footer id="main-footer" class="footer p-5 mt-5 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center lead"> Ptv &copy; <span id="year"></span></p>                   
                </div>
            </div>
        </div>
    </footer>


    
    
<!-- bootstrap core scripts-->
<script src="vendor/js/jquery.min.js"></script>
<script src="vendor/js/bootstrap.min.js"></script>
<script>
    $("#year").text( new Date().getFullYear());
</script>
    
</body>
</html>