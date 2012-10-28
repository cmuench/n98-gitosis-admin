<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \N98\Gitosis\Admin\Web\Application();
$app->run();