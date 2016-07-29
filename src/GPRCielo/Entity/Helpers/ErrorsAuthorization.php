<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 16:30
 */

namespace GPRCielo\Entity\Helpers;


use Cielo\CieloException;

class ErrorsAuthorization
{
    /**
     * @desc Este método verifica o codigo de erro e retorna a ação necessária
     * @param $code
     */
    public static function checkCode(CieloException $e){
        $action = "";

        switch ($e->getCode()){
            case 1 :
                $action = "Contate o banco emissor do cartão";
                break;
            case 17:
                $action = "Verifique seu código de Segurança e tente novamente.";
                break;
            case 12:
                $action = "Verifique a forma de pagamento, pois o número de parcelas solicitado ultrapassa o máximo permitido.";
                break;
            case 14:
                $action = "Houve um problema com a autenticação.";
                break;
            case 55:
                $action = "Verifique o numero do seu cartão";
                break;
            case 95:
                $action = "Ocorreu uma falha, se persistir entrar em contato conosco.";
                break;

            case 97:
            case 98:
            case 99:
                $action = "Ocorreu um erro [".$e->getCode()."], entre em contato com o suporte ";
            default:
                $action = "[".$e->getCode()."] ".$e->getMessage();
        }

        return $action;
    }
}