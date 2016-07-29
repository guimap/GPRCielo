<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 15/07/16
 * Time: 10:59
 */

namespace GPRCielo\Entity;


abstract class EntityBase
{

    abstract public function toArray();

    abstract public function toXML();

    /**
     * @param $array Array de dados para conversão
     * @param \SimpleXMLElement $xml Objeto a ser convertido, indicando o nome da raiz
     * @return String
     */
    protected function arrayToXML($array,  \SimpleXMLElement $xml,$nohRaiz,$isRemove = false){

        foreach ($array as $key => $value) {
            if(is_array($value)){
                if(is_int($key)){
                    $key = "e";
                }
                $label = $xml->addChild($key);
                $this->arrayToXml($value, $label);
            }
            else {
                $xml->addChild($key, $value);
            }
        }

        $strXML = $xml->asXML();
        if($isRemove) {
            //Retira o cabeçalho
            $strXML = str_replace("<?xml version=\"1.0\"?>", "", $strXML);



        }

        return $strXML;
    }
}