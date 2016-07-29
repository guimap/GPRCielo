<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 10:02
 */

namespace GPRCielo\Entity;

use GPRCielo\Entity\EntityBase;

class Pedido extends EntityBase
{
    private $idPedido;
    private $valor;
    private $moeda;
    private $date;
    private $descricao;
    private $idioma;
    private $taxaEmbarque;

    public function __construct()
    {
        $this->moeda = 986;
        $this->idioma = "PT";
    }

    /**
     * @return mixed
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * @param mixed $idPedido
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
        return $this;
    }

    /**
     * @return int
     */
    public function getValor()
    {
        $valor = $this->valor."";
        $valor = money_format("%.2n",$valor);
        $valor = str_replace(".","",$valor);
        return (int) $valor;
    }

    /**
     * @param float $valor
     */
    public function setValor( $valor)
    {
        if(!is_float($valor)){
            throw new \Exception("O Valor deve ser do tipo float");
        }
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoeda()
    {
        return $this->moeda;
    }

    /**
     * @param mixed $moeda
     */
    public function setMoeda($moeda)
    {
        $this->moeda = $moeda;
        return $this;
    }

    /**
     * @param $format define if should format the date
     * @return mixed
     */
    public function getDate($format = null)
    {
        return $format ? $this->date->format($format) : $this->date;
    }

    public function getFormatDate(){
        return $this->date->format("Y-m-d")."T".$this->date->format('H:i:s');
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * @param mixed $idioma
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxaEmbarque()
    {
        return $this->taxaEmbarque;
    }

    /**
     * @param mixed $taxaEmbarque
     */
    public function setTaxaEmbarque($taxaEmbarque)
    {
        $this->taxaEmbarque = $taxaEmbarque;
        return $this;
    }

    public function toArray()
    {
        return [

                "numero"        => $this->getIdPedido(),
                "valor"         => $this->getValor(),
                "moeda"         => $this->getMoeda(),
                "data-hora"     => $this->getFormatDate(),
                "descricao"     => urlencode($this->getDescricao()),
                "idioma"        => $this->getIdioma()


        ];
    }

    public function toXML(){



        $arrPedido = $this->toArray();

        $xml = new \SimpleXMLElement("<dados-pedido/>");

        $xmlStr = $this->arrayToXML($arrPedido,$xml,"<dados-pedido/>",true);


        return $xmlStr;
    }






}