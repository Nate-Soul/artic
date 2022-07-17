<?php 
    require('core/autoload.php');
    $appObj = new App();
    $articleObj = new Articles();
    $catObj = new Category();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--links-->
    <link rel="stylesheet" href="vendor/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="vendor/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<!-- title tag -->
    <title> website label | <?= $appObj->getAppName(); ?> </title>
</head>
<body>
   <!--header-->
   <header id="main-header">
        <nav class="navbar navbar-expand-md white-bg navbar-light fixed-top" id="menu" role="navigation">
                <div class="container">
                    <a href="index.php" class="navbar-brand">
                        <img src="images/logo/logo.png" alt="logo"/>
                    </a>
                    <button class="navbar-toggler" data-toggle="collapse" data-target="#menuToggle">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="menuToggle" class="collapse navbar-collapse">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item px-2">
                                <a href="index.php" class="nav-link current"> Home </a>
                            </li>
                            <li class="dropdown nav-item px-2">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> Categories </a>
                                <div class="dropdown-menu">
                                    <?= $catObj->getCategoryMenu(); ?>            
                                </div>
                            </li>
                            <li class="nav-item px-2">
                                <a href="about.php" class="nav-link"> About </a>
                            </li>
                            <li class="nav-item px-2">
                                <a href="contact.php" class="nav-link"> Contact </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <form action="search.php" method="GET" class="form-inline">
                                <div class="input-group">
                                    <input type="search" name="q" id="search" class="form-control" placeholder="Search Ptv.."/>
                                    <div class="input-group-append">
                                        <button class="btn btn-ente">Search</button>
                                    </div>
                                </div>
                            </form>
                        </ul>
                    </div>
                </div>
            </nav>
       <div class="container">
           <div id="header-container">
                <div id="header-content" class="d-block mx-auto text-center">
                    <h1 class="display-4">Ptv</h1>
                    <p class="lead">making a difference</p>
                </div>
           </div>
       </div>
   </header>

   <!--heading section-->
   <section id="flag" class="bg-light text-danger p-4 mb-3">
       <div class="container">
           <div class="row">
               <div class="col-md-12">
                   <h5 class="text-center"> Stay Updated With Latest News & Articles </h5>
               </div>
           </div>
       </div>
   </section>
    
<!--post section-->
<section id="posts" class="py-5">
    <div class="container">
        <div class="row">               
            <main class="col-lg-9 col-md-9 col-sm-12 mb-4">
			<!-- article -->
            <?php if($articleObj->getArticleCount() > 0){
                $pageObj  = new Paging($articleObj->fetchArticles());
                $articles = $pageObj->getRecords();
                foreach($articles as $article){ ?>
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
                <?php } } ?>
				<!-- article ends here -->
				<!-- pagination -->
                <nav class="nav-pills py-2">
                    <div class="pagination-wrapper row">
                        <div class="col-md-12">
                           <?= $pageObj->getPaging(); ?>
                        </div>
                    </div>
                </nav>
				<!--// pagination ends -->
            </main>
            <aside id="side-content" class="col-lg-3 col-md-3 col-sm-12 mx-auto">
                <ul class="list-group">
                    <h4 class="text-center prim-color cat-title py-2"> Categories  </h4>
                    <li class="list-group-item"><a href="category.php"> Business <span class="badge bg-busi pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Cars <span class="badge bg-cars pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Entertainment <span class="badge bg-ente pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Fashion <span class="badge bg-fash pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Food <span class="badge bg-food pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Lifestyle <span class="badge bg-life pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Politics <span class="badge bg-poli pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Sports <span class="badge bg-spor pull-right p-1">25</span> </a></li>
                    <li class="list-group-item"><a href="category.php"> Technology <span class="badge bg-tech pull-right p-1">25</span> </a></li>
                </ul>
				<!-- advertisement -->
                <div class="my-4 text-center">
                    <small class="text-danger">Advertisement</small>
                    <img src="images/adverts/" class="img-fluid img-thumbnail" alt="">
                </div>
				<!-- // advert ends here -->
            </aside>
        </div>
    </div>
</section>

<footer id="main-footer" class="bg-dark text-light p-5 mt-5">
    <div class="container">
        <div id="footer-content" class="row mb-4">
            <div class="col-md-4">
                <h4 class="cars-color"> Site Links </h4>
                <a href="login.php"> Login </a>  
                <a href="#">About Us </a>   
                <a href="#">Contact Us </a>
                <a href="login.php">Login </a>
                <h4 class="cars-color"> Categories </h3>
                <a href="#">Sports </a>
                <a href="#">Tech </a>
                <a href="#">Politics </a>
                <a href="#">Fashion </a>
                <a href="#">Entertainment </a>
                <a href="#">Business </a>
                <a href="#">Food </a>
                <a href="#">Lifestyle </a>
            </div>
            <div class="col-md-4">
                <h4 class="cars-color">Get Daily Update!</h4>
                <form>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"/><br/>
                    <input type="submit" class="btn btn-danger" value="SUBCRIBE!"/>
                </form>
            </div>
            <div class="col-md-4 Follow-us">
                <h4 class="cars-color"> Follow Us</h4>
                <a href="http://www."> <img src="images/icons/facebook.png" height="36" width="36" alt="F"/></a>
                <a href="http://www."> <img src="images/icons/twitter.png" height="36" width="36" alt="T"/></a>
                <a href="http://www."> <img src="images/icons/instagram.png" height="36" width="36" alt="I"/></a>
                <a href="http://www."> <img src="images/icons/google-plus.png" height="36" width="36" alt="g+"/></a>
            </div>
        </div>
        <p class="lead bg-black-2 text-center py-2">
            Ptv &copy; <span id="year"></span>
        </p>
    </div>
</footer>

    <div class="top">
        <button onClick="topFunction()" id="topBtn" title="go to top"><i class="fa fa-angle-double-up"></i></button>
    </div>
    <!-- bootstrap core scripts-->
  <script src="vendor/js/jquery.min.js"></script>
  <script src="vendor/js/bootstrap.min.js"></script>
  <script src="assets/js/script.js"></script>
  <script>
      $("#year").text(new Date().getFullYear());
  </script>
</body>
</html>