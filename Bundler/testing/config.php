<?php
$CONFIG = Array("NAME" => "TEST");

$BUNDLER_CONFIG_ARRAY = Array (
    "files" => Array (
        "index" => "main.twig",
        "test"   => "test.twig",
	"404"	=> "404.twig",
	"test_dir" => "test_dir/index.twig",
	"test_dir/other" => "test_dir/other.twig"
    ),
    "pageopts_file" => "config.php",
    "static_file_dir" => "/static",
	"twig_dir" => "/twig",
    "base_url" => "working/BlueUtils/Bundler/testing"
);
