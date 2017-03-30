<?php
namespace BluePost;
//TODO: Work with BlueError to stop requiring useless builds
class AjaxResponse {

    private $response = Array();

    /**
     * Builds an AjaxResponse
     * @param array $baseArray An array to start from as the response, will be JSON encoded
     */
    public function __construct($baseArray = Array()) {
        $this->response = $baseArray;
    }

    /**
    * Assert that a test is true, if not, die with an error message
    * @param bool $test - The thing to test if TRUE
    * @param array $error - The error message
    * @param bool $json - Set to FALSE if $error is not an array that should be encoded
    */
   function assert($test, $error, $json = TRUE) {
       if (!$test) {
           if ($json) {
               $this->respond($error);
           }
           else {
               die($error);
           }
       }
   }

   /**
    * Assert that a value is in an array, wrapper for AjaxAssert (is $needle in $haystack, if no, die $error)
    */
   function isGoodVal($needle, $haystack, $error, $json = TRUE) {
       $this->assert(in_array($needle, $haystack), $error, $json);
   }

   /**
    * Assert that a value is a valid key for an array, wrapper for Ajax Assert
    */
   function isGoodKey ($needle, $haystack, $error, $json = TRUE) {
       $this->assert(array_key_exists($needle, $haystack), $error, $json);
   }

   /**
    * Only assert $test if $cond is true, wrapper for AjaxAssert
    */
   function condAssert ($cond, $test, $error, $json = TRUE) {
       if ($cond) $this->assert ($test, $error, $json);
   }

   /**
    * Add a value to the response array
    * @param string $key
    * @param $value
    */
   function addResponse($key, $value) {
       $this->response[$key] = $value;
   }

   /**
    * Send the response
    * @param array $response - Default Array() The response to send as an array to be json_encoded
    * @param bool $add - Default TRUE then megre the response arg and the running response arrray, otherwise it will just use $response
    */
   function respond($response=Array(), $add=TRUE) {
       if ($add) die(json_encode (array_merge ($this->response, $response)));
       die(json_encode($response));
   }

}
