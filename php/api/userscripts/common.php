<?php

$scriptVersion = '1.3';
$gm_include = 'https://*/DeltekTC/TimeCollection.msv';

function getUrlBase(){
  $url  = @( $_SERVER['HTTPS'] != 'on' ) ? 'http://'.$_SERVER['SERVER_NAME'] :  'https://'.$_SERVER['SERVER_NAME'];

  if( !(strpos($url,'http:') !== false && $_SERVER['SERVER_PORT'] == 80) && !(strpos($url,'https:') !== false && $_SERVER['SERVER_PORT'] == 443) ) {
    $url .= ':'.$_SERVER['SERVER_PORT'];
  }

  return $url;
}

// Retrieves the current URL
function getUrl($file) {

  $url = getUrlBase();

  // Now we want to get just the directory
  $uri = $_SERVER['REQUEST_URI'];
  $parts = explode('/', $uri);
  for($i = 0; $i < count($parts) - 1; $i++){
    $url .= $parts[$i] . '/';
  }

  return $url . $file;
}

// Allows users to override $gm_include (or really anything else)
if(file_exists('override.php')){
  include 'override.php';
}

?>