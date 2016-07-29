<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 12:08
 */

namespace GPRCielo\Entity;


use Zend\View\Model\JsonModel;

class Estabelecimento extends EntityBase
{
    private $chave;
    private $key;

    /**
     * @return mixed
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * @param mixed $chave
     */
    public function setChave($chave)
    {
        $this->chave = $chave;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }


    public function toArray()
    {
        return [
          "numero" => $this->getChave(),
           "chave" => $this->getKey()
        ];
    }

    public function toXML()
    {
        $arrayEstabelecimento = $this->toArray();

        $xml = new \SimpleXMLElement("<dados-ec/>");
        return $this->arrayToXML($arrayEstabelecimento,$xml,"dados-ec",true);
    }

    public function toJSON(){

        return $this->getChave();
    }
}