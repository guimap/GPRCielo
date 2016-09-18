<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 18/07/16
 * Time: 10:26
 */

namespace GPRCielo\Builders;


use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\Estabelecimento;
use GPRCielo\Entity\FormaPagamento;
use GPRCielo\Entity\Pedido;
use GPRCielo\Entity\Portador;

class CieloXMLBuilder
{
    private $stringXML;

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
                                $tipoAutorizacao )
    {
        $this->stringXML = "<?xml version=\"1.0\"?>";
        $this->portador = $portador;
        $this->estabelecimento = $estabelecimento;
        $this->pedido = $pedido;
        $this->formaPagamento = $formaPagamento;
        $this->autorizacao = $tipoAutorizacao;
        $this->endereco = $endereco;







    }

    private function cleanXML(){
        $this->stringXML = "<?xml version=\"1.0\"?>";
    }

    public function makeXMLTransacao($isGenerateToken = false,$isCapturar = false){
        $this->cleanXML();

        $isGenerateToken = $isGenerateToken ? "true" :  "false";
        $isCapturar = $isCapturar ? "true" : "false";

        $this->stringXML .= "<requisicao-transacao id=\"a97ab62a-7956-41ea-b03f-c2e9f612c293\" versao=\"1.2.1\" >";
        $this->stringXML .= $this->estabelecimento->toXML();
        $this->stringXML .= $this->portador->toXML();
        $this->stringXML .= $this->pedido->toXML();
        $this->stringXML .= $this->formaPagamento->toXML();

        $this->stringXML .= "<url-retorno>http://localhost:8080</url-retorno>";

        $this->stringXML .= "<autorizar>".$this->autorizacao."</autorizar>";
        $this->stringXML .= "<capturar>".$isCapturar."</capturar>";

        $this->stringXML .= "<gerar-token>{$isGenerateToken}</gerar-token>";

        $this->stringXML .= $this->endereco->toXML();

        $this->stringXML .= "</requisicao-transacao>";



        return $this->stringXML;

    }

    /**
     * @param bool $isCapturar
     * @return string
     */
    public function makeXMLTransacaoWithToken($token,$isCapturar = false){
        $this->cleanXML();


        $isCapturar = $isCapturar ? "true" : "false";

        $this->stringXML .= "<requisicao-transacao id=\"a97ab62a-7956-41ea-b03f-c2e9f612c293\" versao=\"1.2.1\" >";
        $this->stringXML .= $this->estabelecimento->toXML();
        $this->stringXML .= "<dados-portador><token>".$token."</token></dados-portador>";
        $this->stringXML .= $this->pedido->toXML();
        $this->stringXML .= $this->formaPagamento->toXML();

        $this->stringXML .= "<url-retorno>http://loclhost:8080</url-retorno>";

        $this->stringXML .= "<autorizar>".$this->autorizacao."</autorizar>";
        $this->stringXML .= "<capturar>".$isCapturar."</capturar>";



        $this->stringXML .= $this->endereco->toXML();

        $this->stringXML .= "</requisicao-transacao>";



        return $this->stringXML;

    }

    public function makeXMLConsulta($tid){

        $this->cleanXML();

        $this->stringXML .= "<requisicao-consulta  id=\"a97ab62a-7956-41ea-b03f-c2e9f612c293\" versao=\"1.2.1\" >";
        $this->stringXML .= "<tid>".$tid."</tid>";
        $this->stringXML .= $this->estabelecimento->toXML();

        $this->stringXML .= "</requisicao-consulta>";




        return $this->stringXML;

    }

    public function makeXMLCaptura( $pedido, $tid){
        $this->cleanXML();



        $this->stringXML .= "<requisicao-captura  id=\"a97ab62a-7956-41ea-b03f-c2e9f612c293\" versao=\"1.2.1\" >";
        $this->stringXML .= "<tid>".$tid."</tid>";
        $this->stringXML .= $this->estabelecimento->toXML();
        $this->stringXML .= "<valor>".$pedido->getValor()."</valor>";

        $this->stringXML .= "</requisicao-captura>";



        return $this->stringXML;

    }

    public function makeXMLCancelamento( $pedido, $tid){
        $this->cleanXML();

        $this->stringXML .= "<requisicao-cancelamento id=\"39d36eb6-5ae9-4308-89a1-455d299460c0\" versao=\"1.3.0\">";
        $this->stringXML .= "<tid>".$tid."</tid>";
        $this->stringXML .= $this->estabelecimento->toXML();
        $this->stringXML .= "<valor>".$pedido->getValor()."</valor>";

        $this->stringXML .= "</requisicao-cancelamento>";



        return $this->stringXML;

    }


}