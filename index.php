<?php
require_once('config/config.php');

if(isset($_GET['cmd']) and $_GET['cmd'] != '') require_once('models/index.php');
else require_once('views/index.php');

if(DEBUG_MODE)require_once('views/debug.php');
?>