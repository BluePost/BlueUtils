# BlueAjax
Super-Simple framework for making AJAX easier to deal with on the PHP side. It also includes some JS objects for communicating with an AJAX php script built using this lib.

##Getting Started
### Installation with <a href="https://getcomposer.org/">Composer</a> (<b>Recommended</b>)
We recommend you install the package with Composer:
```
composer require bluepost/blueajax
```
### Installation
Alternatively you can download the <a href="https://github.com/BluePost/BlueAjax/releases/latest">Latest Release</a>, unzip it, and upload the src folder to your environment. Then require it for every file for which you need it. 
```php
require_once ('BlueAjax.php');
```

### JavaScript
The javascript components are all in JS/BlueAjax.js file.

## TODO
* Make all AjaxResponse functions use respond()

## Using BlueAjax
The framework is set up to use "success" and "error" to communicate data. Both the php and js sides rely on this. The data is a string, so we
recommend that you use them to communicate the status of the request. This allows for human readable response messages without an unnecessary
booleans being passed back and forth. 
```php
    <?php
    require_once '../BlueAjax.php';
    //Create a response object
    $response = new BluePost\AjaxResponse();
    //Check if the GET index "q" isset, if not, die with an error: '{"error":"q not set"}'
    $response->assert(GETisset("q"), error("q not set"));
    //Check if $_GET["q"] is a correct value (1 or 2), if not die with an error: '{"error":"q invalid"}'
    $response->isGoodVal($_GET["q"], [1,2], error("q invalid"));
    //If $_GET["q"] is 1, then make sure that the GET index "text" is set. If not, die with an error: '{"error":"text not set"}'
    $response->condAssert($_GET["q"] == 1, GETisset("text"), error("text not set"));
    //Respond with an "All good!" message
    $response->respond(success("All good!"));
```

```html
    <script src = "https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src = "/JS/BlueAjax.js"></script>
    <script>
        var req = new GetAjaxRequest ("/src/tests/test.php", {"q": 1, "text":"Text here"})
                .onError(function (f) {console.log(f)})
                .onSuccess(function (s) {console.log(s)})
                .execute()
        
        var AjaxRequest = AjaxRequestFactoryFactory("/src/tests/", 
                //OnError
                function (f) {console.log(f)},
                //OnSuccess
                function (s) {console.log(s)}
            );
        var req = AjaxRequest("test.php", {"q": 1, "text":"Text here"}).execute()
    </script>
```
## API Details
### PHP
#### BluePost\AjaxResponse
* `__construct($baseArray = Array())`
  * The constructor function takes an optional `$baseArray` which will form the inner response object (see `respond`, `addResponse`)
* `assert($test, $error, $json = TRUE)`
  * If `$test` is FALSE then the function will die with `$error`. If `$json` is TRUE, then the function will json_encode `$error`.
* `isGoodVal($needle, $haystack, $error, $json = TRUE)`
  * If `$needle` isn't in `$haystack` the function will die with `$error`. If `$json` is TRUE, then the function will json_encode `$error`.
* `isGoodKey($needle, $haystack, $error, $json = TRUE)`
  * If `$needle` isn't a valid key for the array `$haystack` the function will die with `$error`. If `$json` is TRUE, then the function will json_encode `$error`.
* `condAssert($cond, $test, $error, $json = TRUE)`
  * If `$cond` is true then the function will call `assert($test, $error, $json)`.
* `addResponse($key, $value)`
  * This will add the key-value pair to the inner response object which is used when the respond function is called.
* `respond($response=Array(), $add=TRUE)`
  * This will die with the json_encoded value of `$response` merged with the inner response array (see `addResponse`). If `$add` is False then the inner array won't be used.

#### Isset Functions
* `GETisset($index, $harsh = TRUE)`
  * This function will return TRUE if `$_GET[$index]` isset. If `$harsh` is TRUE then the function will use `strictIsset`
* `POSTisset($index, $harsh = TRUE)`
  * See `GETisset` but with $_POST
* `strictIsset($test, $notZero = FALSE, $notEmptyString = TRUE, $notNull = TRUE)`
    * An isset that has a number of extra tests, controlled by the boolean variables
* `laxIsset ($test, $NES = FALSE)`
  * This uses `strictIsset` with all optional params set to FALSE, except `$notEmptyString`, which is set to `$NES`