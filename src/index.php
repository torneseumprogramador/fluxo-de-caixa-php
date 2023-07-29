<?php
// src/index.php

require 'vendor/autoload.php';

use Slim\Slim;

$app = new Slim();

$app->get('/', function () {
    echo "bem vindo a home";
});

$app->run();
