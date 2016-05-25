<?php
namespace BluePost;

require_once "BlueErrorScheme.php";

function blueErrorArray($codes = array("GE"), $message = "An error occurred") {

    $errorCode = "/";
    foreach($codes as $code) {
        $errorCode .= $code . "/";
    }
    return Array (
        "error" => true,
        "code" => array (
            "string" => $errorCode,
            "sections" => $codes
        ),
        "message" => $message
    );

}

/**
 * @param string $type
 * @param string $subType
 * @param null $section
 * @param string $message
 * @return array
 * @deprecated
 */
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

