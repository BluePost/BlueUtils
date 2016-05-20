<?php
namespace BluePost;

function customError($type = "SE", $subType = "GE", $section = null, $message = "An error occurred") {
    $errorCode = $type . "/" . $subType . ($section != null ? "/" . $section : "");
    return array (
        "error" => true,
        "code"  => array (
            "type" => $type,
            "subtype" => $subType,
            "section" => $section,
            "code" => $errorCode
        ),
        "message" => $message
    );
}

