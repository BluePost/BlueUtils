<?php

require_once __DIR__ . '/../../../BlueUtils.php';
use function BluePost\customError;
//Create a response object
$response = new BluePost\AjaxResponse();
//Check if the GET index "q" isset, if not, die with an error: '{"error":"q not set"}'
$response->assert(GETisset("q"), customError("GE", "GE", null, "q not set"));
//Check if $_GET["q"] is a correct value (1 or 2), if not die with an error: '{"error":"q invalid"}'
$response->isGoodVal($_GET["q"], [1,2], customError("GE", "GE", null, "q invalic"));
//If $_GET["q"] is 1, then make sure that the GET index "text" is set. If not, die with an error: '{"error":"text not set"}'
$response->condAssert($_GET["q"] == 1, GETisset("text"),customError("GE", "GE", null, "text not set"));
//Respond with an "All good!" message
$response->respond(BluePost\customSuccess("All good!"));