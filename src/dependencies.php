<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// mailer
$container['mail'] = function ($c) {
    $settings = $c->get('settings')['mail'];
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $settings['host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = $settings['SMTPAuth'];                               // Enable SMTP authentication
    $mail->Username = $settings['username'];                 // SMTP username
    $mail->Password = $settings['password'];                           // SMTP password
    $mail->SMTPSecure = $settings['SMTPSecure'];                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $settings['port'];
    return $mail;
};
