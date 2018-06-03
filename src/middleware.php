<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Tuupola\Middleware\HttpBasicAuthentication;

$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "users" => $this->users
    ]));
