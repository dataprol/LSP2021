<?php
class MainModel{
    
    var $resultado;
    var $conn;

    function __construct($bd){
        
        require_once("bd/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> openConnect($bd);
        $this -> Conn = $oConexao -> getConnect();

    }

    public function ConsultaPerfil( $clienteId ){

        $sql = "SELECT * 
        FROM tb_perfil 
        WHERE id_perfil = $clienteId";

        $this -> resultado = $this -> Conn -> query( $sql );
    
    }

    public function ConsultaPedido($pedidoId){

        $sql = "SELECT * 
        FROM tb_pedido 
        WHERE id_pedido = $pedidoId ";
        
        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function ListaPedidos($perfilId){
        
        $select = "SELECT * FROM tb_pedido";
        $where = "WHERE ";

        if( $perfilId != null ){
            $where .= "fk_id_perfil_pedido = $perfilId";
        }else{
            $where .= "fk_id_perfil_pedido = id_perfil";
            $select .= ", tb_perfil";
        }
        if( $_SESSION['tipo_cadastro'] == _TIPO_ONG ){
            $where .= " and (status = 0 or id_coleta = ".$_SESSION["id_perfil"].")";
        }
        $where .= " and unix_timestamp(STR_TO_DATE(dt_limite, '%Y-%m-%d %H:%i:%s')) > " . time();

        $sql = "$select 
                $where 
                ORDER BY dt_limite
                LIMIT 25";

        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function ReservarPedido($pedidoId){

        $sql = "UPDATE tb_pedido 
                SET 
                    status = 1, 
                    id_coleta = ".$_SESSION["id_perfil"]."
                WHERE id_pedido = $pedidoId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function DesistirPedido($pedidoId){

        $sql = "UPDATE tb_pedido 
                SET 
                    status = 0, 
                    id_coleta = fk_id_perfil_pedido 
                WHERE id_pedido = $pedidoId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function ObtemConsulta(){
        return $this -> resultado;
    }

}
?>