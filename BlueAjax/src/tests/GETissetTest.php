<?php

require_once __DIR__ . '/../BlueAjax.php';
//Create a response object
$response = new BluePost\AjaxResponse();
//Check if the GET index "q" isset, if not, die with an error: '{"error":"q not set"}'
$response->assert(GETisset("q"), \BluePost\customError("GE", "GE", null, "q not set"));