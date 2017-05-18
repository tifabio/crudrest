<?php
require("vendor/autoload.php");
$swagger = \Swagger\scan('./application');
header('Content-Type: application/json');
echo $swagger;