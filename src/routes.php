<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
  $this->logger->info("GET / ".date("Y-m-d H:i:s"));
  return $response->withStatus(404);
});

$app->post('/', function ($request, $response, $args) {
  $this->logger->info("POST / ".date("Y-m-d H:i:s"));
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
