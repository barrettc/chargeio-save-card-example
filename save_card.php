<?php

require_once 'config.php';

$token_id = $_POST['token_id'];

$responseAttributes = array();
try {
	$save_card = ChargeIO_Card::create(array('token_id' => $token_id));

  session_start();
	$responseAttributes = array_merge($save_card->attributes, array('success' => true));
}
catch(Exception $ex) {
	# Get errors/messages from exception

	$messages = ($ex instanceof ChargeIO_ApiError) ? $ex->messages : array('messages' => array(array('code' => 'application_error', 'level' => 'error')));
	$responseAttributes = array('messages' => $ex->messages);
}

header('Content-type: application/json');
$response = json_encode($responseAttributes, true);
echo $response;
?>
