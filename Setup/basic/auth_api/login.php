<?php

    $SECURE = false;
    require_once (__DIR__ . "/../helpers/header.php");

    $response = new BluePost\AjaxResponse();

    $response->assert( POSTisset("username"), authError()->LOGIN_NOTSET->custom("UN", "Username not set"));
    $response->assert( POSTisset("password"), authError()->LOGIN_NOTSET->custom("PW", "Password not set"));
    $isApp = POSTisset("is_app") && $_POST["is_app"] == 1;

    $loginResponse = $AUTH->loginUser($_POST["username"], $_POST["password"], $isApp);

    $response->respond($loginResponse);

?>
