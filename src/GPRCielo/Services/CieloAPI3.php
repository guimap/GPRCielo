<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 21/07/16
 * Time: 15:28
 */

namespace GPRCielo\Services;


use GPRCielo\Builders\CieloJSONBuilder;
use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\FormaPagamento;
use GPRCielo\Entity\Helpers\Autorizacao;
use GPRCielo\Entity\Pedido;
use GPRCielo\Entity\Portador;

/***
 * Class CieloAPI3
 * @desc A API 3 ainda não esta sendo homologada, portanto essa classe não esta estavel
 * @package GPRCielo\Services
 */
class CieloAPI3 extends CieloAPI
{
    private $estabelecimento;
    private $portador;
    private $pedido;
    private $formaPagamento;
    private $endereco;

    private $host;

    private $builder;
    /**
     * CieloAPI3 constructor.
     * @param $estabelecimento
     * @param Portador $portador
     * @param Pedido $pedido
     * @param FormaPagamento $formaPagamento
     * @param Endereco $endereco
     */
    public function __construct($estabelecimento,
                                Portador $portador,
                                Pedido $pedido,
                                FormaPagamento $formaPagamento,
                                Endereco$endereco){

        $this->estabelecimento = $estabelecimento;
        $this->portador = $portador;
        $this->pedido = $pedido;
        $this->formaPagamento = $formaPagamento;
        $this->endereco = $endereco;

        $this->host = "https://apisandbox.cieloecommerce.cielo.com.br";

        $this->builder = new CieloJSONBuilder(
            $this->estabelecimento,
            $this->portador,
            $this->pedido,
            $this->formaPagamento,
            $this->endereco,
            Autorizacao::AUTHORIZE_WITHOUT_AUTHENTICATION
        );


    }

    public function transacao(){

        $this->builder = new CieloJSONBuilder(
            $this->estabelecimento,
            $this->portador,
            $this->pedido,
            $this->formaPagamento,
            $this->endereco,
            Autorizacao::AUTHORIZE_WITHOUT_AUTHENTICATION
        );

        $json = $this->builder->makeTransacao();
        $path = "/1/sales/";
        $string = $this->makeRequest($json,$path);
//        echo $json;
        echo $string;
    }

    public function makeRequest($json,$path)
    {

        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "MerchantId: " . $this->estabelecimento->getChave();
        $headers[] = "MerchantKey: " . $this->estabelecimento->getKey();
        $headers[] = "RequestId: 12232456-1235-1234-1234-123579547956";

        $url = $this->host.$path;




        $ch = curl_init();
        flush();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        curl_setopt($ch, CURLOPT_TIMEOUT, 40);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $string = curl_exec($ch);

        curl_close($ch);



        //Verifica se Possui algum erro no xml





        return $string;
    }
}