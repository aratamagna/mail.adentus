<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/', function ($request, $response, $args) {
  $this->logger->info("POST / ".date("Y-m-d H:i:s"));
  $input = $request->getParsedBody();

  try {
    //Recipients
    $this->mail->setFrom('noreply@adentus.com', 'Contacto');
    $this->mail->addAddress($input['address']);

    if (!empty($input['attachment'])){
      if (array_key_exists('file', $input['attachment']){
        $attachment = substr($input['attachment']['file'], strpos($input['attachment']['file'], ","));
        if (!array_key_exists('name', $input['attachment'])){
          $name = uniqid();
        } else {
          $name = $input['attachment']['name'];
        }
        if (!array_key_exists('encoding', $input['attachment'])){
          $encoding = "base64";
        }
        if (!array_key_exists('type', $input['attachment'])){
          $type = mime_content_type($name);
        } else {
          $type = $input['attachment']['type'];
        }
        $this->mail->addStringAttachment($attachment, $name, $encoding, $type);
      }
    }

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
