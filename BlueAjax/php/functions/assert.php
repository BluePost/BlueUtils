<?php
/**
 * Assert that a test is true, if not, die with an error message
 * @param bool $test - The thing to test if TRUE
 * @param array $error - The error message
 * @param bool $json - Set to FALSE if $error is not an array that should be encoded
 * @deprecated since version 0.3
 */
function AjaxAssert($test, $error, $json = TRUE) {
    if (!$test) {
        die($json ? json_encode($error) : $error);
    }
}

/**
 * Assert that a value is in an array, wrapper for AjaxAssert (is $needle in $haystack, if no, die $error)
 * @deprecated since version 0.3
 */
function AjaxGoodVal($needle, $haystack, $error, $json = TRUE) {
    AjaxAssert(in_array($needle, $haystack), $error, $json);
}

/**
 * Assert that a value is a valid key for an array, wrapper for Ajax Assert
 * @deprecated since version 0.3
 */
function AjaxGoodKey ($needle, $haystack, $error, $json = TRUE) {
    AjaxAssert(array_key_exists($needle, $haystack), $error, $json);
}

/**
 * Only assert $test if $cond is true, wrapper for AjaxAssert
 * @deprecated since version 0.3
 */
function AjaxCondAssert ($cond, $test, $error, $json = TRUE) {
    if ($cond) AjaxAssert ($test, $error, $json);
}
