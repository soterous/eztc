<?php
  require_once 'config.php';
  require_once 'db.php';

  $db = new DB();

  echo $db->getPotato();
?>