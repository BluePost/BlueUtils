<?php
namespace BluePost;

require_once "BlueErrorScheme.php";

/**
 * Create an array for the error to be json_encoded
 * @param array $codes The codes of the error i.e [GE, NS] will give GE-NS
 * @param string $message The human readable message for the error
 * @return array An array to be json_encoded
 */
function blueErrorArray($codes = array("GE"), $message = "An error occurred") {

    $errorCode = "-";
    $errorNum = "";
    foreach($codes as $code) {
        $errorCode .= $code . "-";
        for ($i=0; $i<strlen($code); $i++) {
            $errorNum .= ord($code[$i]);
        }
    }



    return Array (
        "error" => true,
        "success" => false,
        "code" => array (
            "string" => $errorCode,
            "sections" => $codes
        ),
        "message" => $message,
        "number" => $errorNum
    );

}

/**
 * @param string $type
 * @param string $subType
 * @param null $section
 * @param string $message
 * @return array
 */
function customError($type = "SE", $subType = "GE", $section = null, $message = "An error occurred") {
    $errorCode = $type . "/" . $subType . ($section != null ? "/" . $section : "");
    return array (
        "error" => true,
        "success" => false,
        "code"  => array (
            "type" => $type,
            "subtype" => $subType,
            "section" => $section,
            "code" => $errorCode
        ),
        "message" => $message
    );
}

/**
 * Create an array to act as a success message for the user
 * @param string $message Human readable message for the user
 * @param array $payload Array of data to be sent
 * @return array
 */
function customSuccess($message = "The operation completed successfully", $payload = Array()) {
    $payload["message"] = $message;
    $payload["success"] =  TRUE;
    return $payload;
}
