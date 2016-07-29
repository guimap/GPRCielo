<?php
namespace GPRCielo\Controller;

use Cielo\Cielo;
use GPRCielo\Builders\CieloXMLBuilder;
use GPRCielo\Entity\Endereco;
use GPRCielo\Entity\Estabelecimento;
use GPRCielo\Entity\FormaPagamento;

use GPRCielo\Entity\Helpers\Autorizacao;
use GPRCielo\Entity\Helpers\Indicador;
use GPRCielo\Entity\Portador;



use GPRCielo\Entity\Helpers\Produto;
use GPRCielo\Entity\Helpers\Bandeira ;

// use UNIUSBase\Mail\Mail;
// use VMBPayPalMerchant\Checkout\ExpressCheckout;

use GPRCielo\Services\CieloAPI3;
use PayPal\Api\Transaction;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use UNIUSBase\Entity\User;

use GPRCielo\Entity\Pedido;
use GPRCielo\Services\CieloAPI;





class IndexController extends AbstractActionController
{
    public function indexAction()
    {



    }





}




