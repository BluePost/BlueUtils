<?php
$CONFIG = Array(
	"NAME" => "This is the name of the project",
	"DEBUGGING" => false,
);

$BUNDLER_CONFIG_ARRAY = Array (
	"files" => Array (
		//Hey there maintainer. You may believe it to be a good idea in adding a "users/happy/havefun" page - it works with bundler after all! but you are wrong. Like really very wrong. You couldn't be more wrong in fact. All paths will be broken. Very broken. Such is the lovable nature of relative paths
		//Update: This has been re-tested and confirmed to be the case
		"index" => "main.twig",
		//"404" => "404.twig"
	),
	"pageopts_file" => "config.php",
	"static_file_dir" => "/static",
	"twig_dir" => "/display",
	"base_url" => ""
);

if ($CONFIG['DEBUGGING']) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
