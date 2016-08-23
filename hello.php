<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php
require_once 'config.php';

$responseAttributes = array();
try {
    $card_list = ChargeIO_Card::all();
    $responseAttributes = array_merge($card_list->attributes, array('success' => true));

} catch (Exception $ex) {
  $messages = ($ex instanceof ChargeIO_ApiError) ? $ex->messages : array('messages' => array(array('code' => 'application_error', 'level' => 'error')));
  $responseAttributes = array('messages' => $ex->messages);
}

$response = json_encode($responseAttributes, true);
echo $response;

echo '<p>Hello World</p>';
?>

***************

<div class="chargeio" data-chargeio-payment-form></div>

<?php include 'scripts.php' ?>
    <script type="text/javascript" src="https://api.chargeio.com/assets/api/v1/chargeio.js"></script>
    <script type="text/javascript">
  // Initialize ChargeIO
  ChargeIO.init({
    public_key: '<?php echo ChargeIO::getCredentials()->getPublicKey() ?>',
    debug: true
  });

  (function($) {
    // On DOMReady:
    $(function() {
      // Bind to the 'token' event, which lets you know when a payment token
      // has been created and gives you the token to use in the transaction
      ChargeIO.on('form', 'token', function(event, token) {

        var error_handler = new ChargeIO.PaymentErrorHandler($('form[data-chargeio-ajax]').get(0));
        $.post('save_card.php', {'token_id': token.id}, function(data) {
          // Parse the transaction response JSON and convert it to an object
          var transaction = typeof data === 'string' ? $.parseJSON(data) : data;

          if (!transaction['success']) {
            // Let the error handler scan the response object for errors,
            // then display these errors
            error_handler.handleErrorsFromResponse(transaction);
          } else {
            ChargeIO.trigger('form', 'completed');
            window.location = 'receipt.php';
          }
        }).fail(function() {
          error_handler.handleErrorsFromResponse({'messages': [{'code': "application_error", 'level': "error"}]});
        });
      });

    });

  })(ChargeIO.jQuery);
    </script>

 </body>
</html>
