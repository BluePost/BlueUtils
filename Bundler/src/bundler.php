<?php

require_once (__DIR__ . "/utils.php");
require_once (__DIR__ . "/vendor/autoload.php");

if (php_sapi_name() != "cli") die("This is a cli only application");

//[  FATAL  ]
//[  WARN   ]
//[ SUCCESS ]
//[  INFO   ]

$shortopts = "r:c:yf";

$options = getopt($shortopts);
//var_dump($options);

//Check that a config file is passed, if not assume config.json
if (!isset($options["c"]) || $options["c"] == "") {
    print("[  WARN   ] No config file specified, assuming config.json\n");
    $CONFIG_FILEPATH = "config.json";
}
else {
    $CONFIG_FILEPATH = $options["c"];
}

// Check that a release string is specified
if (!isset($options["r"]) || $options["r"] == "") die("[  FATAL  ] No release string specified\n");
$RELEASE_STRING = $options["r"];

// Read the config file
$CONFIG_FILE = getFileConts($CONFIG_FILEPATH);
if (!$CONFIG_FILE) die("[  FATAL  ] Config file does not exist\n");
$BUNDLER_CONFIG_ARRAY = json_decode($CONFIG_FILE, true);
print("[ SUCCESS ] Config file " . $CONFIG_FILEPATH . " read\n");

//var_dump($BUNDLER_CONFIG_ARRAY);

// Check that files have been specified and if is a directory or an array of files
if (!isset($BUNDLER_CONFIG_ARRAY["files"]) || !is_array($BUNDLER_CONFIG_ARRAY["files"])) die ("[  FATAL  ] Files array is not present in config file\n");
$FILES = $BUNDLER_CONFIG_ARRAY["files"];

if (!isset($BUNDLER_CONFIG_ARRAY["twig_dir"]) || $BUNDLER_CONFIG_ARRAY["twig_dir"] == "" ) die ("[  FATAL  ] Twig directory is not set in config file\n");
$TWIG_DIR = $BUNDLER_CONFIG_ARRAY["twig_dir"];

// Check that a file with the config array is present
if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_file"]) || $BUNDLER_CONFIG_ARRAY["pageopts_file"] == "" ) die ("[  FATAL  ] Page options file is not present in config file\n");
$PAGEOPTS_FILE = $BUNDLER_CONFIG_ARRAY["pageopts_file"];

if (!isset($BUNDLER_CONFIG_ARRAY["pageopts_var"]) || $BUNDLER_CONFIG_ARRAY["pageopts_var"] == "" ) {
    print ("[  WARN  ] Page options variable is not present in config file, assuming CONFIG\n");
    $PAGEOPTS_VAR = "CONFIG";
}
else {
    $PAGEOPTS_VAR = $BUNDLER_CONFIG_ARRAY["pageopts_var"];
}

if (!isset($BUNDLER_CONFIG_ARRAY["static_file_dir"]) || $BUNDLER_CONFIG_ARRAY["static_file_dir"] == "") {
    print ("[  WARN   ] Static directory not specified, assuming 'static/'");
    $STATIC_DIR = "static/";
}
else {
    $STATIC_DIR = $BUNDLER_CONFIG_ARRAY["static_file_dir"];
}

// Display info to user
print ("\n[  INFO   ] Configuration for this bundle:\n");

print ("[  INFO   ] Files = " . implode($FILES, ", ") . "\n");
print ("[  INFO   ] Release number = $RELEASE_STRING\n");
print ("[  INFO   ] Page Options PHP for import = " . $PAGEOPTS_FILE . "\n");
print ("[  INFO   ] Twig directory = $TWIG_DIR\n");

// If the user hasn't overridden, check that they want to continue
if (!isset($options["y"])) {
    print ("\n\nDo you want to continue (y/n)\n");
    $response = readline("y/n >");
    if ($response != "y") die("Exiting...\n");
}
echo("\n");
//Setup twig
$TWIG_LOADER = new Twig_Loader_Filesystem(getcwd() . "/" .$TWIG_DIR);
$TWIG = new Twig_Environment($TWIG_LOADER, Array());

//================================================ Start the bundling ================================================//

//Create the new release folder
$newReleasePath = "releases/" . $RELEASE_STRING;
if  (file_exists($newReleasePath)) {
    if (!isset($options["f"])) {
        die("[  FATAL  ] Release already exisits - Please try again\n");
    }
    else {
        print ("[  WARN   ] Release already exists, deleting\n");
        delTree($newReleasePath);
    }
}


mkdir($newReleasePath, 0777, true);

xcopy(getcwd() . "/" . $STATIC_DIR, $newReleasePath . "/" . $STATIC_DIR);

//FIXME: Fix this horrible thing
require_once $PAGEOPTS_FILE;
$PAGEDATA = Array("CONFIG" => $$PAGEOPTS_VAR);

$count = 0;
foreach ($FILES as $PAGENAME=>$PAGE) {

    //TODO: Optimise
    if ($count == 0) {
        //TODO: Allow naming in config file
        fwrite(fopen($newReleasePath . '/index.html', "w"), $TWIG->render($PAGE, $PAGEDATA));
    }

    else {
        mkdir($newReleasePath . "/" . $PAGENAME,0777,true);
        fwrite(fopen($newReleasePath . "/" . $PAGENAME . '/index.html', "w"),$TWIG->render($PAGE, $PAGEDATA));
    }
    $count++;

    print ("[ SUCCESS ] Bundled file $PAGENAME\n");

}

