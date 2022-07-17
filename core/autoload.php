<?php

	require_once("config.php");

	function autoLoad($className){
		//class directories
		$paths = array(
			"../classes/",
			"classes/",
			" ",
			"../../classes/"
		);
		foreach($paths as $path){
			if(file_exists($path.$className.".php")){
		require_once ($path.str_replace("_", "/" ,$className).".php");
			}
		}
	}
	
   spl_autoload_register("autoLoad");
 
?>