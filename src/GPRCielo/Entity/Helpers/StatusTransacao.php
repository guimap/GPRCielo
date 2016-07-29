<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 25/07/16
 * Time: 14:51
 */

namespace GPRCielo\Entity\Helpers;


class StatusTransacao
{
    /**
     * @desc Pega a descrição do status
     * @param $status
     */
    public static function get($status){
        $statusMSG = "Ocorreu um erro";
        switch ($status){
            case "0":
                $statusMSG = "Criada";
                break;
            case "1":
                $statusMSG = "Em Andamento";
                break;
            case "2":
                $statusMSG = "Autenticada";
                break;
            case "3":
                $statusMSG = "Não Autenticada";
                break;
            case "4":
                $statusMSG = "Autorizada";
                break;
            case "5":
                $statusMSG = "Não Autorizada";
                break;
            case "6":
                $statusMSG = "Capturada";
                break;
            case "9":
                $statusMSG = "Cancelada";
                break;
            case "10":
                $statusMSG = "Em Autenticação";
                break;
            case "12":
                $statusMSG = "Em Cancelamento";
                break;
        }

        return $statusMSG;

    }
}