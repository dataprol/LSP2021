<?php

class PedidosController{
        
    var $PedidoModel;
    var $sisConfig;

    public function __construct($cfgsis){

        require_once("models/PedidosModel.php");
        $this -> PedidoModel = new PedidosModel($cfgsis['banco_de_dados']);    

        $this -> sisConfig = $cfgsis;

    }

    public function Index(){

        require_once("views/header.php");
        require_once("views/pedidos/ListaPedidos.php");
        require_once("views/footer.php");

    }

    public function ListaPedidos(){
        
        if( !isset($_SESSION["usuarioNomeLogin"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PedidoModel -> ListaPedidos($_SESSION["id_perfil"]);

        $result = $this -> PedidoModel -> ObtemConsulta();
        
        $arrayPedidos = array();
        while( $linha = $result -> fetch_assoc() ) {
            array_push( $arrayPedidos, $linha );
        }            
        
        require_once("views/header.php");
        require_once("views/pedidos/ListaPedidos.php");
        require_once("views/footer.php");
        
    }      
  
    public function InserePedido(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }
        
        require_once("views/header.php");
        require_once("views/pedidos/InserePedido.php");
        require_once("views/footer.php");

    }

    public function InserePedidoAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PedidoModel -> InserePedido( $_POST );
        
        $idPedido = $this -> PedidoModel -> ObtemConsulta();
        
        if( $idPedido > 0 ){

            $this -> ReportaSucesso( "pedido cadastrado, com sucesso!", null, "?c=p&a=l" );

        }else{

            $this -> ReportaFalha(null,null);

        }

    }

    public function ReportaFalha( $cMensagemDeErro, $cTituloDoErro ){

        require_once("views/header.php");
        require_once("views/falha.php");
        require_once("views/footer.php");        

    }

    public function ReportaSucesso( $cMensagemDeSucesso, $cTituloDoSucesso, $cCaminhoDoBotaoSucesso ){
        
        require_once("views/header.php");
        require_once("views/sucesso.php");
        require_once("views/footer.php");        

    }

}

?>