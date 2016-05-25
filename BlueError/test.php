<?php
require "BlueError.php";
$a = new \BluePost\ErrorScheme("A", "This is error A");
$b = new \BluePost\ErrorScheme("B", "This is error B", $a);
$c = new \BluePost\ErrorScheme("C", "This is error C", $b);

var_dump(json_encode($a->build()));
var_dump(json_encode($b->build()));
var_dump(json_encode($c->build()));