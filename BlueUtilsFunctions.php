<?php
function randomstring($length = 10, $stringonly = false) { //Generate a random string
  $characters = 'abcdefghkmnopqrstuvwxyzABCDEFGHKMNOPQRSTUVWXYZ';
  if (!$stringonly) $characters .= '0123456789';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
   $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function sanitizestring($var) {
 global $GLOBALS;
 $var = strip_tags($var);
 $var = htmlentities($var);
 $var = stripslashes($var);
 return mysqli_real_escape_string($GLOBALS['CONN'], $var);
}

function cleanstring($var) {
 global $GLOBALS;
 $config = HTMLPurifier_Config::createDefault();
 $config->set('Cache.DefinitionImpl', null);
 $config->set('AutoFormat.Linkify', true);
 $purifier = new HTMLPurifier($config);
 $clean_html = $purifier->purify($var);
 $clean_html = urlencode($clean_html);
 return mysqli_real_escape_string($GLOBALS['CONN'], $clean_html);
}
