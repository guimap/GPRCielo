<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 18/07/16
 * Time: 10:38
 */

namespace GPRCielo\Entity;


class Endereco extends EntityBase
{
    private $endereco;
    private $complemento;
    private $numero;
    private $bairro;
    private $cep;
    /**
     * @var Portador
     */
    private $portador;

    public function __construct(Portador $portador)
    {
        $this->portador = $portador;
    }

    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param mixed $endereco
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param mixed $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * @return Portador
     */
    public function getPortador()
    {
        return $this->portador;
    }

    /**
     * @param mixed $portador
     */
    public function setPortador($portador)
    {
        $this->portador = $portador;
        return $this;
    }


    public function toArray()
    {

        return [
            "endereco"      => $this->getEndereco(),
            "complemento"   => $this->getComplemento(),
            "numero"        => $this->getNumero(),
            "bairro"        => $this->getBairro(),
            "cep"           => $this->getCep(),
            "cpf"           => $this->getPortador()->getCPF()
        ];
    }

    public function toXML()
    {
        $str = "<avs><![CDATA[
            <dados-avs>
              <endereco>".urlencode($this->getEndereco())."</endereco>
              <complemento>".urlencode($this->getComplemento())."</complemento>
              <numero>".urlencode($this->getNumero())."</numero>
              <bairro>".urlencode($this->getBairro())."</bairro>
              <cep>".urlencode($this->getCep())."</cep>
              <cpf>".urlencode($this->getPortador()->getCpf())."</cpf>
            </dados-avs>
          ]]></avs>";
        return $str;
    }
}