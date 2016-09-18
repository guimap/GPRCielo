<?php
ini_set('display_errors',true);
require "../autoload.php";




    $estabelecimento = new \GPRCielo\Entity\Estabelecimento();
    $estabelecimento->setEstabelecimento('1006993069')
        ->setKey('25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3');

    $validadeCartao = new \DateTime('2018-05-01');
    $portador = new \GPRCielo\Entity\Portador();
    $portador->setCodigoSeguranca('123')
        ->setNome('GPR Test')
        ->setNumeroCartao('4012001037141112')
        ->setIndicador(\GPRCielo\Entity\Helpers\Indicador::INFORMADO)
        ->setValidadeCartao($validadeCartao)
        ->setNomeImpresso('GPR Cielo');

    $pedido = new \GPRCielo\Entity\Pedido();
    $pedido->setDescricao('Only demo Test')
        ->setIdPedido(uniqid('test'))
        ->setDate(new \DateTime('now'))
        ->setValor(1.00);

    $forma = new \GPRCielo\Entity\FormaPagamento();
    $forma->setBandeira(\GPRCielo\Entity\Helpers\Bandeira::VISA)
        ->setParcelas(1)
        ->setProduto(\GPRCielo\Entity\Helpers\Produto::CREDITOVISTA);

    $endereco = new \GPRCielo\Entity\Endereco($portador);

    $cieloAPI = new \GPRCielo\Services\CieloAPI(
        $estabelecimento,
        $portador,
        $pedido,
        $forma,
        $endereco,
        \GPRCielo\Entity\Helpers\URLCielo::DEVELOPMENT
    );


    $pedido = new \GPRCielo\Entity\Pedido();
    $pedido->setValor(1.00)
        ->setDescricao("Our Test");

//    $cieloAPI->setDebug(true);

    $json = $cieloAPI->transacao();
    $json = $cieloAPI->consulta('10069930690007E69B6A');
    $json = $cieloAPI->cobrar($pedido,'10069930690007E69B6A');
//    $json = $cieloAPI->cancelar('10069930690007E6937A',$pedido);
//    $json = $cieloAPI->consulta('10069930690007E6937A');

echo "<pre>";
var_dump($json);
echo "</pre>";

