<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Require in TWIG and the config file
require_once (__DIR__ . "/vendor/autoload.php");
require_once (__DIR__ . "/config.php");

//Check that inside the config file a config array is present
if (!isset($BUNDLER_CONFIG_ARRAY)) die("[  FATAL  ] Config array not set\n");

//Check that a twig dir is defined
if (!isset($BUNDLER_CONFIG_ARRAY["twig_dir"]) || $BUNDLER_CONFIG_ARRAY["twig_dir"] == "" ) die ("[  FATAL  ] Twig directory is not set in config file\n");
$TWIG_DIR = $BUNDLER_CONFIG_ARRAY["twig_dir"];

// Check that files have been specified and if is a directory or an array of files
if (!isset($BUNDLER_CONFIG_ARRAY["files"]) || !is_array($BUNDLER_CONFIG_ARRAY["files"])) die ("[  FATAL  ] Files array is not present in config file\n");
$FILES = $BUNDLER_CONFIG_ARRAY["files"];

// Check that a file with the config array is present
if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_file"]) || $BUNDLER_CONFIG_ARRAY["pageopts_file"] == "" ) die ("[  FATAL  ] Page options file is not present in config file\n");
$PAGEOPTS_FILE = $BUNDLER_CONFIG_ARRAY["pageopts_file"];

//See if a variable for the page options isset
if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_var"]) || $BUNDLER_CONFIG_ARRAY["pageopts_var"] == "" ) {
    $PAGEOPTS_VAR = "CONFIG";
}
else {
    $PAGEOPTS_VAR = $BUNDLER_CONFIG_ARRAY["pageopts_var"];
}

//Setup twig
$TWIG_LOADER = new Twig_Loader_Filesystem(getcwd() . "/" .$TWIG_DIR);
$TWIG = new Twig_Environment($TWIG_LOADER, Array());

//Parse the request and find the file to run
$pathFinderRequest = parse_url($_SERVER['REQUEST_URI']);
$pathFinderPath = $pathFinderRequest["path"];

$pathFinderResult = trim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $pathFinderPath), '/');
$pathFinderResult = explode('/', $pathFinderResult);

$pathFinderMax_level = 5;
while ($pathFinderMax_level < count($pathFinderResult)) {
    unset($pathFinderResult[0]);
}

//Require the pageoptions file (if it is the orriginal config file this wont happen
require_once $PAGEOPTS_FILE;
if (!isset($$PAGEOPTS_VAR)) $$PAGEOPTS_VAR = Array();
$PAGEDATA = Array("CONFIG" => $$PAGEOPTS_VAR);

//Select the correct file for rendering
$pathFinderResult = implode('/', $pathFinderResult);

if (isset($BUNDLER_CONFIG_ARRAY["base_url"]))
    $pathFinderResult = str_replace($BUNDLER_CONFIG_ARRAY["base_url"], "", $pathFinderResult);

if ($pathFinderResult == "")
    $PAGE = array_values($FILES)[0];
elseif (!isset($PAGES[$pathFinderResult]))
    $PAGE = $FILES['404'];
else
    $PAGE = $FILES[$pathFinderResult];

//Render the page
die($TWIG->render($PAGE, $PAGEDATA));