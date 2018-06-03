<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "users" => $this->users
    ]));
