# Blue Error
A library that allows formalisation of error codes for use in APIs.
 
## Roadmap
* Search the errors by code (and message?)
* More efficient way of building the errors (only on use?)
* Import from an external file

## Usage
The main function of BlueError is `blueErrorArray` which returns an array of the error which can be passed through `json_encode` and returned by the API. This just takes in strings so the use of
`BlueErrorScheme` is encouraged so that errors can be defined in advance. To see a sample usage of this see `Examples/auth_lib_errors.php`.

```php
<?php
require "BlueError.php";
$a = new \BluePost\ErrorScheme("A", "This is error A");
$b = new \BluePost\ErrorScheme("B", "This is error B", $a);
$c = new \BluePost\ErrorScheme("C", "This is error C", $b);

var_dump(json_encode($a->build()));
var_dump(json_encode($b->build()));
var_dump(json_encode($c->build()));
```

