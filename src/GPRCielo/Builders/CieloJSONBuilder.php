<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 22/07/16
 * Time: 15:20
 */

namespace GPRCielo\Builders;


use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\Estabelecimento;
use GPRCielo\Entity\FormaPagamento;
use GPRCielo\Entity\Pedido;
use GPRCielo\Entity\Portador;

/***
 * Class CieloJSONBuilder
 * @desc A API 3 ainda não esta sendo homologada, portanto essa classe não esta estavel
 * @package GPRCielo\Services
 */
class CieloJSONBuilder
{
    private $portador;
    private $estabelecimento;
    private $pedido;
    private $formaPagamento;
    private $autorizacao;
    private $endereco;
    /**
     * CieloXMLBuilder constructor.
     * @param Estabelecimento $estabelecimento
     * @param Portador $portador
     * @param Pedido $pedido
     * @param FormaPagamento $formaPagamento
     * @param Endereco $endereco
     * @param $autorizacao Use a classe GPRCielo\Entity\Helpers\Autorizacao;
     */
    public function __construct(Estabelecimento $estabelecimento,
                                Portador $portador,
                                Pedido $pedido,
                                FormaPagamento $formaPagamento,
                                Endereco $endereco,
                                $tipoAutorizacao ) {
        $this->portador = $portador;
        $this->estabelecimento = $estabelecimento;
        $this->pedido = $pedido;
        $this->formaPagamento = $formaPagamento;
        $this->autorizacao = $tipoAutorizacao;
        $this->endereco = $endereco;
    }


    public function makeTransacao($isToken = false, $isCapturar = false){
        try {
            $array = [];

            $mustSavedCard = $isToken ?  "true" : "false";

            //Dados do estabelecimento
            $array["MerchantOrderId"] =  $this->estabelecimento->getChave();
            $array["MerchantKey"] =  $this->estabelecimento->getKey();
            $array["Customer"] = $this->portador->toJSON();


            $array["Payment"] = [
              "Type"            => "CreditCard", //TODO Deixar dinamico via Parametro
               "Amount"         => $this->pedido->getValor(),
               "Provider"       => "Simulado ", //TODO Verificar os tipos de providers
               "Authenticate"   => true,
               "Installments"   => 1 //Quantidade de parcelas
            ];

            $array["Payment"]["CreditCard"] = [
                "CardNumber"        => $this->portador->getNumeroCartao(),
                "Holder"            => $this->portador->getNomeImpresso(),
                "ExpirationDate"    => $this->portador->getValidadeCartao("m/Y"),
                "SecurityCode"      => $this->portador->getCodigoSeguranca(),
                "Brand"             => $this->formaPagamento->getBandeira(),
                "SaveCard"          => $mustSavedCard
            ];




            return json_encode($array);
        }catch (\Exception $e){

        }

    }
}