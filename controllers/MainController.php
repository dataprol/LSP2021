<?php

    class MainController{

        var $MainModel;
        var $sisConfig;
    
        public function __construct( $sisConfig ){
        
            require_once("models/MainModel.php");
            $this -> MainModel = new MainModel( $sisConfig['banco_de_dados'] );
            
            $this -> sisConfig = $sisConfig;

        }
    
        public function Index(){

            if( !isset($_SESSION["usuarioNomeLogin"]) ){
                header("Location: index.php?c=m&a=l");exit;
            }

            if( $_SESSION['id_perfil'] > 0 ){
                $this -> MainModel -> ConsultaPerfil( $_SESSION['id_perfil'] );
                $result = $this -> MainModel -> ObtemConsulta();
                if( $result != false ){
                    $cadastroPerfil = $result -> fetch_assoc();
                    $_SESSION['endereco_perfil'] = $cadastroPerfil['endereco'];

                    // Paginação
                    $nItensPorPagina = 2;
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG ){
                        $this -> MainModel -> ContaPedidos(null);
                    }else{
                        $this -> MainModel -> ContaPedidos($_SESSION["id_perfil"]);
                    }
                    $linha = $this -> MainModel -> ObtemConsulta() -> fetch_assoc();
                    $nTotalItens = $linha["total_linhas"];
                    $nTotalPaginas = ceil( $nTotalItens / $nItensPorPagina );
                    if( !isset($_GET["pag"]) or $_GET["pag"] < 1 ){
                        $nPagina = 1;
                    }else{
                        $nPagina = $_GET['pag'];
                    }
                    if( $nPagina > $nTotalPaginas ){
                        $nPagina = $nTotalPaginas;
                    }

                    // Listagem
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG ){
                        $this -> MainModel -> ListaPedidos(null,$nItensPorPagina*($nPagina-1),$nItensPorPagina);
                    }else{
                        $this -> MainModel -> ListaPedidos($_SESSION["id_perfil"],$nItensPorPagina*($nPagina-1),$nItensPorPagina);
                    }
                    $result = $this -> MainModel -> ObtemConsulta();                    
                    $arrayPedidos = array();
                    while( $linha = $result -> fetch_assoc() ) {
                        array_push( $arrayPedidos, $linha );
                    }            
            
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG ){ 
                        require_once("views/header.php");
                        require_once("views/home.php");
                        require_once("views/footer.php");        
                    }else{
                        header("Location: index.php?c=p&a=l");exit;
                    }
                    
                }else{
                    $this -> ReportaFalha('houve algum problema na consulta do perfil do usuário!',null);
                }
            }else{
                $this -> ReportaFalha(null,null);
            }

        }

        public function Login(){

            require_once("views/usuarios/LoginHeader.php");
            require_once("views/usuarios/Login.php");

        }

        public function AcessoNegado($nivel){
            
            $this -> ReportaFalha('acesso restrito aos usuários com nível '.strtolower(array_search($nivel,_USUARIOS_LISTA_NIVEIS)).'.','Acesso negado');

        }

        public function ReservarPedido( $pedidoId ){

            if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
                header("Location: index.php?c=m&a=l");exit;
            }
    
            negarAcesso("Operacional");

            $this -> MainModel -> ConsultaPedido($pedidoId);
            if( $result = $this -> MainModel -> ObtemConsulta() ){
                $aPedido = $result -> fetch_assoc();
                if( $aPedido['status'] == 0 ){
                    if( $aPedido['dt_limite'] <= date('Y-m-d H:i:s') ){
                        $this -> ReportaFalha("data limite é menor que atual","Prazo Esgotado");
                    }else{
                        $this -> MainModel -> ReservarPedido($pedidoId);
                        if( $this -> MainModel -> ObtemConsulta() != false ){
                            $this -> ReportaSucesso("doação reservada, com sucesso!",null,"?c=m&a=i");
                            //header("Location: index.php?c=m&a=i");
                        }else{
                            $this -> ReportaFalha(null,null);
                        }                
                    }
                }else{
                    $this -> ReportaFalha("a doação não está mais disponível.<br>Já foi ". 
                            strtolower(array_search($aPedido["status"],_PEDIDOS_SITUACOES)).
                            ".","Indisponível");
                }
            }else{
                $this -> ReportaFalha(null,null);
            }

        }

        public function DesistirPedido( $pedidoId ){
    
            if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
                header("Location: index.php?c=m&a=l");exit;
            }
    
            negarAcesso("Operacional");
            
            $this -> MainModel -> DesistirPedido($pedidoId);
            if( $this -> MainModel -> ObtemConsulta() != false ){
                //$this -> ReportaSucesso("doação abandonada, com sucesso!",null,"?c=m&a=i");
                header("Location: index.php?c=m&a=i");
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
                    
        public function DestroySession(){

            $_SESSION = array();
            session_destroy();
            header("Location: index.php?c=m&a=l");exit;

        }

    }
    
?>