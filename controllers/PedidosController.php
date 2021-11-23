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

            //$this -> ReportaSucesso( "doação cadastrada, com sucesso!", null, "?c=p&a=l" );
            header("Location: index.php?c=p&a=l");

        }else{

            $this -> ReportaFalha(null,null);

        }

    }

    public function AtualizaPedido( $id_pedido ){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PedidoModel -> ConsultaPedido( $id_pedido );
        $result = $this -> PedidoModel -> ObtemConsulta();

        if( $arrayPedido = $result -> fetch_assoc() ){
            require_once("views/header.php");
            require_once("views/pedidos/AlteraPedido.php");
            require_once("views/footer.php");
        }else{
            $this -> ReportaFalha(null,null);
        }

    }

    public function AtualizaPedidoAction(){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PedidoModel -> AtualizaPedido($_POST);
        if( $this -> PedidoModel -> ObtemConsulta() != false ){            
            //$this -> ReportaSucesso("doação atualizada, com sucesso!",null,"?c=p&a=l");
            header("Location: index.php?c=p&a=l");
        }else{
            $this -> ReportaFalha('houve uma falha na tentativa de atualização da doação!',null);
        }        
        
    }
    
    public function FinalizarPedido( $pedidoId ){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        $this -> PedidoModel -> ConsultaPedido($pedidoId);
        if( $result = $this -> PedidoModel -> ObtemConsulta() ){
            $aPedido = $result -> fetch_assoc();
            if( $aPedido['status'] != 1 ){
                $this -> ReportaFalha("a doação não pode ser finalizada quando ". 
                        strtolower(array_search($aPedido["status"],_PEDIDOS_SITUACOES)).
                        ".","Indisponível");
            }else{
                $this -> PedidoModel -> FinalizarPedido($pedidoId);
                if( $this -> PedidoModel -> ObtemConsulta() != false ){
                    $this -> ReportaSucesso("finalizou a coleta da doação, com sucesso!",null,"?c=p&a=l");
                }else{
                    $this -> ReportaFalha(null,null);
                }
            }
        }else{
            $this -> ReportaFalha(null,null);
        }

    }

    public function CancelarPedido( $pedidoId ){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Gerencial");

        $this -> PedidoModel -> ConsultaPedido($pedidoId);
        if( $result = $this -> PedidoModel -> ObtemConsulta() ){
            $aPedido = $result -> fetch_assoc();
            if( $aPedido['status'] > 0 ){
                $this -> ReportaFalha("a doação não pode ser cancelada quando ". 
                        strtolower(array_search($aPedido["status"],_PEDIDOS_SITUACOES)).
                        ".","Indisponível");
            }else{
                $this -> PedidoModel -> CancelarPedido($pedidoId);
                if( $this -> PedidoModel -> ObtemConsulta() != false ){
                    //$this -> ReportaSucesso("doação cancelada, com sucesso!",null,"?c=p&a=l");
                    header("Location: index.php?c=p&a=l");
                }else{
                    $this -> ReportaFalha(null,null);
                }
            }
        }else{
            $this -> ReportaFalha(null,null);
        }

    }

    public function RemovePedido( $pedidoId ){

        if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
            header("Location: index.php?c=m&a=l");exit;
        }

        negarAcesso("Administrativo");
        
        $this -> PedidoModel -> ConsultaPedido($pedidoId);
        if( $result = $this -> PedidoModel -> ObtemConsulta() ){
            $aPedido = $result -> fetch_assoc();
            if( $aPedido['status'] == 8 ){
                $this -> PedidoModel -> RemovePedido($pedidoId);
                if( $this -> PedidoModel -> ObtemConsulta() != false ){
                    //$this -> ReportaSucesso("doação removida, com sucesso!",null,"?c=p&a=l");
                    header("Location: index.php?c=p&a=l");
                }else{
                    $this -> ReportaFalha(null,null);
                }                
            }else{
                $this -> ReportaFalha("a doação não pode ser removida quando ". 
                        strtolower(array_search($aPedido["status"],_PEDIDOS_SITUACOES)).
                        ".","Indisponível");
            }
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