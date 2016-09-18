<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 12:07
 */

namespace GPRCielo\Entity;


use GPRCielo\Entity\Helpers\Bandeira;
use GPRCielo\Entity\Helpers\Produto;
use GPRCielo\Services\GPRCielo;

class FormaPagamento extends EntityBase
{
    private $bandeira;
    private $produto;
    private $parcelas;


    public function __construct()
    {
        $this->parcelas = 1;
    }

    /**
     * @return mixed
     */
    public function getBandeira()
    {
        return $this->bandeira;
    }

    /**
     * @param mixed $bandeira
     */
    public function setBandeira($bandeira)
    {
        $this->bandeira = $bandeira;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @desc Código do produto: 1 – Crédito à Vista, 2 – Parcelado loja, A – Débito, defaul is 1
     * @param  $prod
     * @return $this
     * @internal param Produto $produto
     */
    public function setProduto($prod)
    {
        $this->produto = $prod;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * @param mixed $parcelas
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
        return $this;
    }


    public function toArray()
    {
        return [
            "bandeira"  => $this->getBandeira(),
            "produto"   => $this->getProduto(),
            "parcelas"  => $this->getParcelas()
        ];
    }

    public function toXML()
    {
        $arrayForma = $this->toArray();
        $xml = new \SimpleXMLElement("<forma-pagamento/>");
        $xmlStr = $this->arrayToXML($arrayForma,$xml,"forma-pagamento",true);
        return $xmlStr;
    }
}