<?php
class PedidosModel{
    
    var $resultado;
    var $Conn;

    function __construct($bd){
        
        require_once("bd/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> openConnect($bd);
        $this -> Conn = $oConexao -> getConnect();

    }

    public function ListaPedidos($perfilId){
        
        $sql = "SELECT * FROM tb_pedido 
                WHERE fk_id_perfil_pedido = $perfilId
                LIMIT 50";
                //ORDER BY usuarioNome LIMIT 25";
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function InserePedido($arrayPedido){

        $sql = "INSERT INTO tb_pedido
        (
        descricao,
        status,
        dt_cadastro,
        dt_limite,
        fk_id_perfil_pedido
        )
        VALUE(
        '" . $arrayPedido['pedidoDescricao'] . "', 
        0, 
        '" . date('Y-m-d H:i:s') . "', 
        '" . $arrayPedido['pedidoDataLimite'] . "', 
        " . $arrayPedido['perfilId'] . "
        );";

        $this -> Conn -> query($sql);
        
        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function ObtemConsulta(){
        return $this -> resultado;
    }

}
?>