<?php
include 'common.php';
?>
// ==UserScript==
// @name        EZTC
// @namespace   com.soterous.eztc
// @description Pushes each DELTEK timecard to the EZTC server
// @include     <?php echo $gm_include . "\n"; ?>
// @version     <?php echo $scriptVersion."\n"; ?>
// @downloadURL <?php echo getUrl('eztc.user.js')."\n"; ?>
// @updateURL   <?php echo getUrl('eztc.meta.js')."\n"; ?>
// @require  http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js
// @grant    GM_xmlhttpRequest
// ==/UserScript==