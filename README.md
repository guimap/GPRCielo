# GPRCielo ZF2
GPRZF2 is a module for zend 2, that makes integration with 
cielo gateway

# Criando o objeto CIELOAPI
a short code to instance an cieloService
```
//Defining estabelecimento credentials
$estabelecimento = new \GPRCielo\Entity\Estabelecimento();
$estabelecimento->setEstabelecimento('1006993069')
      ->setKey('25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3');

//Create a portador object
$validadeCartao = new \DateTime('2018-05-01');
$portador = new \GPRCielo\Entity\Portador();
$portador->setCodigoSeguranca('123')
        ->setNome('GPR Test')
        ->setNumeroCartao('4012001037141112')
        ->setIndicador(\GPRCielo\Entity\Helpers\Indicador::INFORMADO)
        ->setValidadeCartao($validadeCartao)
        ->setNomeImpresso('GPR Cielo');

//Creating a Pedido (Order) 
$pedido = new \GPRCielo\Entity\Pedido();
$pedido->setDescricao('Only demo Test')
    ->setIdPedido(uniqid('test'))
    ->setDate(new \DateTime('now'))
    ->setValor(1.00);


//Set a FormaPagamento 
$forma = new \GPRCielo\Entity\FormaPagamento();
$forma->setBandeira(\GPRCielo\Entity\Helpers\Bandeira::VISA)
    ->setParcelas(2)
    ->setProduto(\GPRCielo\Entity\Helpers\Produto::PARCELADO);

//We need define a address, even if this address is empty
$endereco = new \GPRCielo\Entity\Endereco($portador);

/*Then we create the cieloAPI object
 *here we're using the development URL, 
 *but we can use the Production URL using the \GPRCielo\Entity\Helpers\URLCielo::PRODUCTION
 */  
$cieloAPI = new \GPRCielo\Services\CieloAPI(
    $estabelecimento,
    $portador,
    $pedido,
    $forma,
    $endereco,
    \GPRCielo\Entity\Helpers\URLCielo::DEVELOPMENT
);
```

# Transações
    ```
    $result = $cieloAPI->transacao();
    ```

## Para gerar uma token do cartão passe true no primeiro parametro
``` 
$result = $cieloAPI->transacao(true)
```



## Para gerar uma token do cartão e cobrar essa trasação
```
$result = $cieloAPI->transacao(true,true);
```
Esse método retorna um array contendo o TID, PAN, Statud da trasação e o token (caso seja pedido)


# Consulta
```
$cieloAPI->consulta($tid);
```
# Cobrar
Para Cobrar, é necessário passar um objeto GRPCielo\Entity\Pedido 
para o método cobrar, pode-se cobrar o valor total ou parcial da transação
```
$cieloAPI->cobrar($pedido,'10069930690007E69B6A');
```

# Cancelar
Para cancelar é  preciso passar um objeto GRPCielo\Entity\Pedido 
para o método cancelar, é possível cancelar parte do valor da transação.
```
$cieloAPI->cancelar('10069930690007E6937A',$pedido)
```