<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 11:12
 */

namespace GPRCielo\Entity;


class Portador extends EntityBase
{
    //Required => 	Número do cartão.
    private $numero;

    //Required => Validade do cartão no formato aaaamm. Exemplo: 201212 (dez/2012).
    /**
     * @var \DateTime
     */
    private $validade;

    //Required => Indicador sobre o envio do Código de segurança: 0 – não informado, 1 – informado, 2 – ilegível, 9 – inexistente
    private $indicador;

    //Obrigatório se o indicador for 1
    private $codigoSeguranca;

    //Nome como impresso no cartão (Opcional)
    private $nome;

    private $nomeImpresso;



    /**
     * @var Endereco
     */
    private $endereco;

    /*
     * ( Condicional )
     * 	Token que deve ser utilizado em substituição aos dados do cartão para uma autorização direta
     * ou uma transação recorrente. Não é permitido o
     * envio do token junto com os dados
     * do cartão na mesma transação.
     * */
    private $token;

    private $cpf;



    public function __construct()
    {
        $this->numero           = "";
        $this->validade         = "";
        $this->indicador        = "";
        $this->codigoSeguranca  = "";
        $this->nome             = "";
        $this->token            = "";

    }

    /**
     * @return mixed
     */
    public function getNomeImpresso()
    {
        return $this->nomeImpresso;
    }

    /**
     * @param mixed $nomeImpresso
     */
    public function setNomeImpresso($nomeImpresso)
    {
        $this->nomeImpresso = $nomeImpresso;
        return $this;
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
    }

    /**
     * @return mixed
     */
    public function getNumeroCartao()
    {
        return $this->numero;
    }

    /**
     * @desc Pega os 4 primeiros numeros do cartão
     */
    public function getBinCartao(){
        return (int) substr($this->getNumeroCartao(),0,4);
    }

    public function getCartaoTruncado(){
        //Pega o numero de asterisco que vou por
        $total = strlen($this->numero) - 4;
        $asterisk = str_repeat("*",$total);

        $truncado = preg_replace('/^([0-9]{12})/i',$asterisk,$this->numero);
        $strTruncado = preg_replace('/(\*{4})(\*{4})(\*{4})([0-9]{4})/i','$1.$2.$3.$4',$truncado);

        return $strTruncado;
    }
    /**
     * @param mixed $numero
     */
    public function setNumeroCartao($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @param $format Formato da data
     * @return \DateTime
     */
    public function getValidadeCartao($format=false)
    {
        return $format == false ? $this->validade : $this->validade->format($format);
    }

    /**
     * @param mixed $validade
     */
    public function setValidadeCartao(\DateTime $validade)
    {
        $this->validade = $validade;
        return $this;
    }

    /**
     * @desc Retorna o ano da validade do cartão
     * @return string
     */
    public function getAnoValidadeCartao(){
        return $this->validade->format("Y");
    }

    /**
     * @desc Retorna o mês da validade do cartão
     * @return string
     */
    public function getMesValidadeCartao(){
        return $this->validade->format("m");
    }

    /**
     * @return mixed
     */
    public function getIndicador()
    {
        return $this->indicador;
    }

    /**
     * @param mixed $indicador
     */
    public function setIndicador($indicador)
    {
        $this->indicador = $indicador;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeguranca()
    {
        return $this->codigoSeguranca;
    }

    /**
     * @param mixed $codigoSeguranca
     */
    public function setCodigoSeguranca($codigoSeguranca)
    {
        $this->codigoSeguranca = $codigoSeguranca;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        $this->nomeImpresso = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }



    public function toArray()
    {
        return [
            "numero"            => $this->getNumeroCartao(),
            "validade"          => $this->getAnoValidadeCartao().$this->getMesValidadeCartao(),
            "indicador"         => $this->getIndicador(),
            "codigo-seguranca"  => $this->getCodigoSeguranca()

        ];
    }

    public function toXML()
    {
        $arrayPortador = $this->toArray();


        $xml = new \SimpleXMLElement("<dados-portador/>");
        $xmlStr = $this->arrayToXML($arrayPortador,$xml,"<dados-portador/>",true);

//        echo "<pre>";
//        var_dump($xmlStr);
//        echo "</pre>";

        return $xmlStr;
    }

    public function toJSON(){
        return [
          "Name"    => $this->getNome()
        ];
    }
}