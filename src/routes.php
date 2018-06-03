<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $response;
});


$app->post('/', function ($request, $response, $args) {
    $input = $request->getParsedBody();

    try {
    //Recipients
    $this->mail->setFrom('noreply@adentus.com', 'Contacto');
    $this->mail->addAddress($input['address']);

    //Content
    $this->mail->isHTML($input['isHTML']);
    $this->mail->Subject = $input['subject'];
    $this->mail->Body    = $input['body'];
    $this->mail->AltBody = $input['alt'];

    if(!$this->mail->send()) {
        $respcod = 1;
        $respmsg = 'Mailer error: ' . $mail->ErrorInfo;
    } else {
        $respcod = 0;
        $respmsg = 'Message has been sent.';
    }
	} catch (Exception $e) {
	    echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
	}

    $resp = array('code' => $respcod, 'message' => $respmsg);
    $response = $response->withJson($resp, 200);
    return $response;
});
