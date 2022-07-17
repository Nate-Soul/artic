<?php

//All database tables
/* array(
 "table_category" = "categories",
 "table_admin"    = "admin",
 "table_articles" = "articles",
 "table_author"   = "author",
 "table_app"      = "app",
); */


// site domain name with http
$urli = $_SERVER["REQUEST_URI"];
$urli = explode("/", $urli);
$urli = $urli[1];

// set site link with http(s)
$link = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on"
 ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/" . $urli;

defined("SITE_URL")
	|| define("SITE_URL", $link);

?>