<?php
  require_once 'config.php';
  require_once 'db.php';

  $foo = new DB();

  echo 'My Id is: ' . $foo->getUserId('foo2') . '<br>';
?>