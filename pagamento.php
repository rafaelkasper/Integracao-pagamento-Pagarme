<?php

header('Content-Type: text/html; charset=UTF-8');

require("vendor/autoload.php"); 





$captureAmount = $_POST['value'];

$token = $_POST['token']; //Token enviado pelo checkout pagar.me

$nome = $_POST['nome'];

$email = $_POST['email'];

$id = $_POST['id'];

$rua = $_POST['rua'];

$numero = $_POST['numero'];

$estado = $_POST['estado'];

$cidade = $_POST['cidade'];

$bairro = $_POST['bairro'];

$cep = $_POST['cep'];

$cpf = $_POST['cpf'];



$pagarme = new PagarMe\Client('insira sua chave aqui');



try{



    $transaction = $pagarme->transactions()->create([

        'amount' => $captureAmount,

        'card_hash' => $token ,

        'payment_method' => 'credit_card',

        'customer' => [

          'name' => $nome, 

          'email' => $email,  

            'external_id' => $id,

            'type' => 'individual',

            'country' => 'br',

            'documents' => [

                [

                  'type' => 'cpf',

                  'number' => $cpf

                ]

              ],

            'phone_numbers' => [ '+551199999999' ]

        ],

        'billing' => [

            'name' => $nome,

            'address' => [

              'country' => 'br',

              'street' => $rua,

              'street_number' => $numero,

              'state' => $estado,

              'city' => $cidade,

              'neighborhood' => $bairro,

              'zipcode' => $cep

            ]

        ],

        'items' => [

            [

              'id' => '1',

              'title' => 'Serviço de transporte',

              'unit_price' => 1,

              'quantity' => 1,

              'tangible' => false

            ]

        ]

    ]);

    

$status = $transaction->status;

      $file = fopen('token.txt', 'w');

    fwrite($file, $status."\n");

    fclose($file);

 

     /* Em caso de sucesso, retorna o ID da transação para a página do checkout */

     

     echo $status;

            }



            catch (Exception $exception) {



              /* Em caso de erro, retorna o erro para a página do checkout */

              $exception = json_decode($exception->getMessage());

          

              foreach ($exception->errors as $error) {

                  echo $error->message;

              }

            header  ($error);

          }