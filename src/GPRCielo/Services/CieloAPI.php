<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 15:01
 */

namespace GPRCielo\Services;

use Cielo\Cielo;
use Cielo\CieloException;
use Cielo\Transaction;
use Cielo\Holder;
use Cielo\PaymentMethod;
use GPRCielo\Builders\CieloXMLBuilder;
use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\Estabelecimento;
use GPRCielo\Entity\FormaPagamento;
use GPRCielo\Entity\Helpers\Autorizacao;
use GPRCielo\Entity\Helpers\ErrorsAuthorization;
use GPRCielo\Entity\Helpers\StatusTransacao;
use GPRCielo\Entity\Helpers\URLCielo;
use GPRCielo\Entity\Pedido;
use GPRCielo\Entity\Portador;
use Zend\Form\View\Helper\Form;

class CieloAPI
{

    private $portador;
    private $pedido;
    private $formaPagamento;
    private $endereco;
    private $url;

    private $isDebug;

    /**
     * @var Cielo
     */
    private $cielo;

    /**
     * @var CieloXMLBuilder
     */
    private $build;

    /**
     * @desc CieloAPI constructor.
     * @param Estabelecimento $estabelecimento
     * @param Portador $portador
     * @param Pedido $pedido
     * @param FormaPagamento $formaPagamento
     * @param Endereco $endereco
     * @param URLCielo $url
     */
    public function __construct(Estabelecimento $estabelecimento,
                                Portador $portador,
                                Pedido $pedido,
                                FormaPagamento $formaPagamento,
                                Endereco$endereco,
                                $url = URLCielo::PRODUCTION
                                )
    {

        $this->estabelecimento = $estabelecimento;
        $this->portador = $portador;
        $this->pedido = $pedido;
        $this->formaPagamento = $formaPagamento;
        $this->endereco = $endereco;
        $this->url = $url;

        $this->isDebug = false;

        //Crio o builder de XML da CIELO
        $this->buildXML();



    }

    /**
     * @desc Faz uma transacao para o WEB SERVICE


     * @param $token boolean Indica se a transação vai ser feita usando uma token
     * @param $captura boolean Indica se quando a transação for feita ela será capturada ou não
     * @return Array
     * @throws \Exception
     */
    public function transacao($token = false,$captura = false)
    {
        try{

            //Crio um xml para autenticação e recebo a String
            if(!$token){
                $xml = $this->build->makeXMLTransacao(true,$captura);
            }else {
                //É necessário url encode, pois token possuem caracters especiais, segundo o suporte da CIELO
                $token = urlencode($token);
                $xml = $this->build->makeXMLTransacaoWithToken($token,$captura);
            }

            if($this->isDebug){
//                header("Content-Type: xml");
//                echo "<textarea>";
//                echo $xml;
//                echo "</textarea>";

//
//                die;
            }


            //Faço a requisição para o webservice
            //Da pau somente quando eu passo a varivel, se eu fizer assim
            /* =>  $xmlObj = $this->makeRequest('<?xml version="1.0"?><requisicao-transacao id="a97ab62a-7956-41ea-b03f-c2e9f612c293" versao="1.2.1" > <dados-ec><numero>1006993069</numero><chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave></dados-ec> <dados-portador><numero>4012001037141112</numero><validade>201805</validade><indicador>1</indicador><codigo-seguranca>123</codigo-seguranca></dados-portador> <dados-pedido><numero>57c71f074d4cf</numero><valor>9400</valor><moeda>986</moeda><data-hora>2016-08-31T15:16:39</data-hora><descricao>Contrata%C3%A7%C3%A3o+de+servi%C3%A7o+de+limpeza+b%C3%A1sica</descricao><idioma>PT</idioma></dados-pedido> <forma-pagamento><bandeira>visa</bandeira><produto>1</produto><parcelas>1</parcelas></forma-pagamento> <url-retorno>http://localhost:8080</url-retorno><autorizar>3</autorizar><capturar>true</capturar><gerar-token>true</gerar-token><avs><![CDATA[ <dados-avs> <endereco></endereco> <complemento></complemento> <numero></numero> <bairro></bairro> <cep></cep> <cpf></cpf> </dados-avs> ]]></avs></requisicao-transacao>');*/

            $xmlObj = $this->makeRequest($xml);

            if($this->isDebug){
                echo "<pre>";
                var_dump($xmlObj);
                echo "</pre>";
            }

            return [
                "tid"           => $xmlObj['tid'],
                "pan"           => $xmlObj['pan'],
                "status"        => $xmlObj['status'],
                "token"         => $xmlObj['token']['dados-token']['codigo-token'],
                "status-token"  => $xmlObj['token']['dados-token']['status']
            ];

        }catch(\Exception $e){
            throw $e;
        }

    }

    /**
     * @desc Define if the output of request should be printed
     * @param $isDegug
     */
    public function setDebug($isDegug){
        $this->isDebug = $isDegug;
    }

    public function consulta($tid){
        try{

            $xmlString = $this->build->makeXMLConsulta($tid);

            $retorno = $this->makeRequest($xmlString);

            $status = $retorno["status"];
            $retorno["status"]  = StatusTransacao::get($status);
            return $retorno;

        }catch (\Exception $e){
            throw $e;
        }
    }

    public function cobrar($pedido, $tid){

        try{
            $xmlString = $this->build->makeXMLCaptura($pedido,$tid);
            $retorno = $this->makeRequest($xmlString);


            return $retorno;

        }catch (\Exception $e){
            throw $e;
        }
    }

    public function cancelar($tid,$pedido){
        try{
            $xmlString = $this->build->makeXMLCancelamento($pedido,$tid);
            $retorno = $this->makeRequest($xmlString);

            return $retorno;
        }catch (\Exception $e){
            throw $e;
        }
    }


    public function makeRequest($xmlStr){
        $xmlStr = preg_replace( "/\r|\n/", "", $xmlStr);
        $headers = [];
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  "mensagem=".$xmlStr);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);
//        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        $string = curl_exec($ch);
        $d = curl_getinfo($ch);
        curl_close($ch);

        $string = utf8_encode($string);
        $xml = simplexml_load_string($string);
        //Verifica se Possui algum erro no xml
        $regex = "/<erro/i";
        preg_match($regex,$string,$match);
        if( count($match) > 0 ){
            $msg = "[".$xml->codigo."] ".$xml->mensagem;
            throw new \Exception($msg);
        }
        $arr = $this->toArray($xml);
        return $arr;
    }

    private function toArray($xmlObject){
        $out = array();

        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->toArray( $node ) : $node;

        return $out;
    }

    /**
     * @var Estabelecimento
     */
    private $estabelecimento;

    /**
     * @return Estabelecimento
     */
    public function getEstabelecimento()
    {
        return $this->estabelecimento;
    }

    /**
     * @param Estabelecimento $estabelecimento
     */
    public function setEstabelecimento($estabelecimento)
    {
        $this->estabelecimento = $estabelecimento;
        $this->buildXML();
    }

    /**
     * @return Portador
     */
    public function getPortador()
    {
        return $this->portador;
    }

    /**
     * @param Portador $portador
     */
    public function setPortador($portador)
    {
        $this->portador = $portador;
        $this->buildXML();
    }

    /**
     * @return Pedido
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * @param Pedido $pedido
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;
        $this->buildXML();
    }

    /**
     * @return FormaPagamento
     */
    public function getFormaPagamento()
    {
        return $this->formaPagamento;
    }

    /**
     * @param FormaPagamento $formaPagamento
     */
    public function setFormaPagamento($formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;
        $this->buildXML();
    }

    /**
     * @return Endereco
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param Endereco $endereco
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        $this->buildXML();
    }

    /**
     * @desc função que monta o builder de xml de acordo com as informações passadas.
     */
    private function buildXML(){
        $this->build = new CieloXMLBuilder(
            $this->estabelecimento,
            $this->portador,
            $this->pedido,
            $this->formaPagamento,
            $this->endereco,
            Autorizacao::AUTHORIZE_WITHOUT_AUTHENTICATION );
    }
}