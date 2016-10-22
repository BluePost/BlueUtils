<?php

require_once("BlueUtilsSettings/BlueUtilsSettings.php");

global $BLUEUTILS_SETTINGS;
$BLUEUTILS_SETTINGS = new BluePost\BlueUtilsSettings();

global $modules_avaliable;
$modules_avaliable = [
  "ajax"  => "/BlueAjax/php/BlueAjax.php",
  "auth"  => "/BlueAuth/auth_lib.php",
  "email" => "/BlueEMail/BlueEmail.php",
  "error" => "/BlueError/BlueError.php"
];

function get_BU_module($name) {
  global $modules_avaliable;
  return __DIR__ . $modules_avaliable[$name];
}

if (isset($BluePost_Modules)) {

  foreach ($BluePost_Modules as $module) {
    if ($module == "all"){
      foreach ($modules_avaliable as $modname => $modpath){
        require_once __DIR__ . $modpath;
      }
    } else {
      require_once get_BU_module($module);
    }
  }

}
