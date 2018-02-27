<?php
// remove in production
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require_once("hokphp/Autoloader.php");
$params = require_once("hokphp/core/params/params.php");

$app = new \hokphp\core\components\Application($params);
$app->startApplication($params);
