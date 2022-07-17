<?php

require ("core/autoload.php");

$errors      = array();
$loginObj    = new Login();
$adminObj    = new Admins();
$formObj     = new Forms();
$menu        = new Category();

if(isset($_POST["loginDashboard"])){

    //init/validate variables
    $login    = $formObj->validateField($_POST["login"]);
    $password = $_POST["passKey"];
    
	/*
	if (isset($_POST["remember"])) {
		// set cookie one week (value in seconds)
		session_set_cookie_params('604800');
		session_regenerate_id(true);
	} */

    if(empty($login) || empty($password)){
        $errors[] = "Fields Cannot Be Empty";
    }
    if(!preg_match("/^[a-zA-Z0-9\/-_\s]+$/", $login) && !filter_var($login, FILTER_VALIDATE_EMAIL)){
        $errors[] = "login must be a valid username/email address";
    }
    //checkifuserexist
    $checkUser    = $adminObj->checkUserByLogin($login);
    $userDetails  = $adminObj->fetchUserByLogin($login);
    $userPassword = $adminObj->fetchPassword($userDetails["admin_id"]);
    $checkPsw     = password_verify($password, $userPassword);
    if($checkUser != 1 || $checkPsw === false){
        $errors[] = "Username/Password is incorrect";
    }
    /*if($checkUser == 1 && $userDetails["activated"] == 0){
        $errors[] = "You're Registered, activate your account from your email";
    }*/
    if(empty($errors) === true && $checkUser == 1 && $checkPsw === true /*&& $userDetails["activated"] == 1*/){
        $loginObj->loginAdmin($login);
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--links-->
    <link rel="stylesheet" href="vendor/css/fontawesome.min.css">
    <link rel="stylesheet" href="vendor/css/bootstrap.min.css">
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
                                    <?= $menu->getCategoryMenu(); ?>                       
                                 </div>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link" href="About.php"> About </a>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link" href="contact.php"> Contact </a>
                            </li>
                            <li class="nav-item px-2">
                                <a class="nav-link current" href="admin/"> My Account </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!--header-->
            <header id="header" class="py-3 fancyHeader">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h4> Admin Login </h4>
                            <p class="lead"> Fill Credentials To Access Admin Features.</p>
                        </div>
                    </div>
                </div>
            </header>
        
            <!--action section-->
            <section id="action" class="py-4 mb-3 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col"></div>
                    </div>
                </div>
            </section>
        
<!--admin login section-->
<section id="adminLogin" class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto my-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> Admin Login </h4>
                        </div>
                        <div class="card-body">
                        <?php Helper::displayErrors($errors);
                        if(isset($_SESSION["msg"]["err"])){ ?>
                            <div class="alert alert-warning alert-dismissible">
                                <button class="close" data-dismiss="alert" type="button">&times;</button>
                                <?= $_SESSION["msg"]["err"]; unset($_SESSION["msg"]["err"]); ?>
                            </div>
                        <?php } ?>
                            <form action="<?php Forms::post2self(); ?>" method="POST">
                                <div class="form-group">
                                    <label for="login"> Login </label>
                                    <input type="text" name="login" id="login" placeholder="Username/Email Address" class="form-control" 
                                    <?php Helper::stickyText("login"); ?>/>
                                </div>
                                <div class="form-group">
                                    <label for="passKey"> Password </label>
                                    <input type="password" name="passKey" id="passKey" placeholder="Enter Password" class="form-control" 
                                    <?php Helper::stickyTextAlt("passKey"); ?>/>
                                </div>
                                <div class="form-group">
                                    <label for="remember">
                                        <input type="checkbox" name="remember" id="remember" <?php if(isset($_POST["remember"])){ echo "checked=\"checked\""; } ?>> Keep Me Logged In
                                    </label>
                                </div>
                                <button type="submit" name="loginDashboard" class="btn btn-ente btn-block"> Login </button>
                            </form>
                        </div>
                    </div>
                </div>
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