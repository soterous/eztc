<?php
require_once 'config.php';
require_once 'db.php';
echo json_encode($db->getAllEmployees());
?>