<?php
require("../vendor/autoload.php"); 


$captureAmount = $_POST['value'];

$token = $_POST['token']; //Token enviado pelo checkout pagar.me

$nome = $_POST['nome'];

$email = $_POST['email'];

$pagarme = new PagarMe\Client('ak_test_0mvh91MU3yzE3Sq6UXN6rtdvzC9n2v');

try{
    $transaction = $pagarme->transactions()->create([
        'amount' => $captureAmount,
        'card_hash' => $token ,
        'payment_method' => 'credit_card',
        'customer' => [
          'name' => $nome, 
          'email' => $email  ]
      ]);

    /* Em caso de sucesso, retorna o ID da transação para a página do checkout */
   // $transaction->getId();
    $transaction->charge();
    $status = $transaction->status;

    if( strcasecmp($status, 'refused') == 0 ){
        echo '"Pagamento recusado. Tente outro cartão."';
    }
    else{
        echo '"Pagamento aprovado. Em breve o produto estará em suas mãos."';
    }

} catch (Exception $exception) {

    /* Em caso de erro, retorna o erro para a página do checkout */
    $exception = json_decode($exception->getMessage());

    foreach ($exception->errors as $error) {
        echo $error->message;
    }
    header('HTTP/1.0 400 Falha ao capturar a transação');
}




