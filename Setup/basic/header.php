<?php

//DEV ONLY - Remeber to turn off
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($SECURE)) $SECURE = false;
if (!isset($POST)) $POST = true;

require_once (__DIR__ . "/BlueUtilsFunctions.php");

$BluePost_Modules = ["all"];
require_once(__DIR__ . "/BlueUtils.php");

require_once(__DIR__ . "/../vendor/autoload.php");

//Establish connection to the DB

$CONN = new mysqli (
  $BLUEUTILS_SETTINGS->DB_CONFIG["HOST"],
  $BLUEUTILS_SETTINGS->DB_CONFIG["USERNAME"],
  $BLUEUTILS_SETTINGS->DB_CONFIG["PASSWORD"],
  $BLUEUTILS_SETTINGS->DB_CONFIG["NAME"]
);



//Create the instance of the db library
$db = new MysqliDb ($CONN);
$DBLIB = $db;

$CONFIG = Array();

//Get the user's IP
$CONFIG["USER"]["IP"] = (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR']);

//Get the auth credentials from either GET or POST
//TODO: Use ternary operator?
if ($POST) {
    if (isset($_POST["TOKEN_STRING"])) $TS = $_POST["TOKEN_STRING"]; else $TS = null;
    if (isset($_POST["TOKEN_USERID"])) $TU = $_POST["TOKEN_USERID"]; else $TU = null;
    if (isset($_POST["TOKEN_INSTALLHASH"])) $TIH = $_POST["TOKEN_INSTALLHASH"]; else $TIH = null;
    if (isset($_POST["TOKEN_INSTALLHASH_NONCE"])) $TIHN = $_POST["TOKEN_INSTALLHASH_NONCE"]; else $TIHN = null;
}
else {
    if (isset($_GET["TOKEN_STRING"])) $TS = $_GET["TOKEN_STRING"]; else $TS = null;
    if (isset($_GET["TOKEN_USERID"])) $TU = $_GET["TOKEN_USERID"]; else $TU = null;
    if (isset($_GET["TOKEN_INSTALLHASH"])) $TIH = $_GET["TOKEN_INSTALLHASH"]; else $TIH = null;
    if (isset($_GET["TOKEN_INSTALLHASH_NONCE"])) $TIHN = $_GET["TOKEN_INSTALLHASH_NONCE"]; else $TIHN = null;
}

global $GLOBALS;
$GLOBALS = Array(
    "CONN" => $CONN,
    "CONFIG" => $CONFIG,
    "DBLIB" => $db
);

//Create the AUTH instance
$AUTH = new BluePost\AuthLib($db, $TS, $TU, $TIH, $TIHN, $CONFIG["USER"]["IP"]);

//If the page is meant to be secure, make sure that the user is logged in
if ($SECURE) {
    $loggedInResult = $AUTH->checkLogin();
    if (!(isset($loggedInResult["valid"]) && $loggedInResult["valid"])) die(json_encode($loggedInResult));
}

$GLOBALS["AUTH"] = $AUTH;
$GLOBALS["USER"] = $AUTH->user();
$USERDATA = $GLOBALS["USER"];
