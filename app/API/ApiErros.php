<?php
  namespace App\API;
  class ApiErros{
    public static function erroMensageCadastroEmpresa($message, $code){

      return [
        "data" => [
          'mensagem' => $message,
          'codigo' => $code
        ]
      ];

    } 
  }
?>