<?php

require_once './../vendor/autoload.php';

$config = [
    'storage_path' => __DIR__ . '/../data',
];

$app = new \Counters\Web\WebApplication($config);
$app->run();