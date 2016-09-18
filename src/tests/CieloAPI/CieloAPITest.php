<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 18/09/16
 * Time: 01:10
 */
namespace Tests\CieloAPI;

require '../autoload.php';



use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\Estabelecimento;
use GPRCielo\Entity\FormaPagamento;
use GPRCielo\Entity\Helpers\Bandeira;
use GPRCielo\Entity\Helpers\Indicador;
use GPRCielo\Entity\Helpers\Produto;
use GPRCielo\Entity\Helpers\URLCielo;
use GPRCielo\Entity\Pedido;
use GPRCielo\Entity\Portador;
use GPRCielo\Services\CieloAPI;

use PHPUnit_Framework_TestCase as PHPUnit;

class CieloAPITest extends PHPUnit
{
    /**
     * @var CieloAPI
     */
    private $cieloAPI;

    private $tid;

    public function setUp(){
        $estabelecimento = new Estabelecimento();
        $estabelecimento->setEstabelecimento('1006993069')
            ->setKey('25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3');

        $validadeCartao = new \DateTime('2018-05-01');
        $portador = new Portador();
        $portador->setCodigoSeguranca('123')
            ->setNome('GPR Test')
            ->setNumeroCartao('4012001037141112')
            ->setValidadeCartao($validadeCartao)
            ->setIndicador(Indicador::INFORMADO)
            ->setNomeImpresso('GPR Cielo');

        $pedido = new Pedido();
        $pedido->setDescricao('Only demo Test')
            ->setIdPedido(uniqid('test'))
            ->setDate(new \DateTime('now'))
            ->setValor(1.00);

        $forma = new FormaPagamento();
        $forma->setBandeira(Bandeira::VISA)
            ->setParcelas(1)
            ->setProduto(Produto::CREDITOVISTA);

        $endereco = new Endereco($portador);

        $this->cieloAPI = new CieloAPI(
            $estabelecimento,
            $portador,
            $pedido,
            $forma,
            $endereco,
            URLCielo::DEVELOPMENT
        );
    }

    /**
     * @author Guilherme
     *
     */
    public function testTransacao(){
        $pedido = new Pedido();
        $pedido->setValor(1.00)
            ->setDescricao("Our Test");

        $json = $this->cieloAPI->transacao();
        $tid = "";
        if(isset($json['tid'])){
            $tid = $json['tid'];
        }

        $this->assertArrayHasKey('tid',$json);
        return $tid;
    }

    public function testConsulta(){
        $json = $this->cieloAPI->consulta('10069930690007E6937A');
        $this->assertArrayHasKey('dados-pedido',$json);
    }

    /**
     * @depends testTransacao
     */
    public function testCobrarTID($tid){
        $pedido = new \GPRCielo\Entity\Pedido();
        $pedido->setValor(1.00)
            ->setDescricao("Our Test");
        $json = $this->cieloAPI->cobrar($pedido,$tid);
        $json = $this->cieloAPI->consulta($tid);
        if(isset($json['status'])){
            $this->assertEquals('Capturada',$json['status']);
        }else {
            $this->assertArrayHasKey('dados-pedido',$json);
        }

    }

    /**
     * @depends testTransacao
     */
    public function testCancelarTransacao($tid){
        $pedido = new \GPRCielo\Entity\Pedido();
        $pedido->setValor(1.00)
            ->setDescricao("Our Test");
        $json = $this->cieloAPI->cancelar($tid,$pedido);
        $json = $this->cieloAPI->consulta($tid);
        if(isset($json['status'])){
            $this->assertEquals('Cancelada',$json['status']);
        }else {
            $this->assertArrayHasKey('status',$json);
        }
    }
}