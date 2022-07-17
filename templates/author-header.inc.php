<?php $appObj = new App(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--links-->
    <link rel="stylesheet" href="../vendor/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendor/css/bootstrap.min.css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" media="all">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <title>Ptv Admin Area | <?php echo $page; ?> </title>
</head>
<body>
    <?php include "author-nav.inc.php"; ?>
<!--header-->
<header id="header" class="fancyHeader py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4> Admin Dashboard </h4>
                <p class="lead"> Manage <?php echo $page; ?>.</p>
            </div>
        </div>
    </div>
</header>

<!--action section-->
<section id="action" class="py-4 mb-3 bg-dark">
    <div class="container">
        <div class="row">