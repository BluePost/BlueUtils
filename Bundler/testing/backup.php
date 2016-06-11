<?php
require_once (__DIR__ . "/vendor/autoload.php");
require_once (__DIR__ . "/config.php");

if (!isset($BUNDLER_CONFIG_ARRAY)) die("[  FATAL  ] Config array not set\n");

if (!isset($BUNDLER_CONFIG_ARRAY["twig_dir"]) || $BUNDLER_CONFIG_ARRAY["twig_dir"] == "" ) die ("[  FATAL  ] Twig directory is not set in config file\n");
$TWIG_DIR = $BUNDLER_CONFIG_ARRAY["twig_dir"];

// Check that files have been specified and if is a directory or an array of files
if (!isset($BUNDLER_CONFIG_ARRAY["files"]) || !is_array($BUNDLER_CONFIG_ARRAY["files"])) die ("[  FATAL  ] Files array is not present in config file\n");
$FILES = $BUNDLER_CONFIG_ARRAY["files"];

// Check that a file with the config array is present
if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_file"]) || $BUNDLER_CONFIG_ARRAY["pageopts_file"] == "" ) die ("[  FATAL  ] Page options file is not present in config file\n");
$PAGEOPTS_FILE = $BUNDLER_CONFIG_ARRAY["pageopts_file"];

if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_var"]) || $BUNDLER_CONFIG_ARRAY["pageopts_var"] == "" ) {
    $PAGEOPTS_VAR = "CONFIG";
}
else {
    $PAGEOPTS_VAR = $BUNDLER_CONFIG_ARRAY["pageopts_var"];
}

//Setup twig
$TWIG_LOADER = new Twig_Loader_Filesystem(getcwd() . "/" .$TWIG_DIR);
$TWIG = new Twig_Environment($TWIG_LOADER, Array());


$pathFinderRequest = parse_url($_SERVER['REQUEST_URI']);
$pathFinderPath = $pathFinderRequest["path"];

$pathFinderResult = trim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $pathFinderPath), '/');
$pathFinderResult = explode('/', $pathFinderResult);

$pathFinderMax_level = 5;
while ($pathFinderMax_level < count($pathFinderResult)) {
    unset($pathFinderResult[0]);
}

//FIXME: Fix this horrible thing
require_once $PAGEOPTS_FILE;
$PAGEDATA = Array("CONFIG" => $$PAGEOPTS_VAR);

$pathFinderResult = implode('/', $pathFinderResult);

if ($pathFinderResult == "")
    $PAGE = array_values($FILES)[0];
elseif (!isset($PAGES[$pathFinderResult]))
    $PAGE = $PAGES['404'];
else
    $PAGE = $PAGES[$pathFinderResult];
die($TWIG->render($PAGE, $PAGEDATA));