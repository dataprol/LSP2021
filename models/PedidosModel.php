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

    public function ConsultaPedido($pedidoId){

        $sql = "SELECT * 
        FROM tb_pedido 
        WHERE id_pedido = $pedidoId ";
        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function ContaPedidos($perfilId){
        
        $sql = "SELECT COUNT(*) as total_linhas FROM tb_pedido
                WHERE fk_id_perfil_pedido = $perfilId";
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function ListaPedidos($perfilId,$nInicio,$nTotalItens){
        
        $sql = "SELECT * FROM tb_pedido, tb_perfil 
                WHERE fk_id_perfil_pedido = $perfilId AND tb_perfil.id_perfil = tb_pedido.id_coleta     
                ORDER BY dt_cadastro 
                LIMIT $nInicio, $nTotalItens";
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function InserePedido($arrayPedido){

        $sql = "INSERT INTO tb_pedido
        (
        descricao,
        status,
        dt_cadastro,
        dt_limite,
        fk_id_perfil_pedido,
        id_coleta
        )
        VALUE(
        '" . $arrayPedido['pedidoDescricao'] . "', 
        0, 
        '" . date('Y-m-d H:i:s') . "', 
        '" . $arrayPedido['pedidoDataLimite'] . "', 
        " . $arrayPedido['perfilId'] . ",
        " . $arrayPedido['perfilId'] . "
        );";

        $this -> Conn -> query($sql);
        
        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function AtualizaPedido($arrayPedido){

        $sql = "UPDATE tb_pedido 
        SET 
            dt_limite='" . $arrayPedido['pedidoDataLimite'] . "', 
            descricao='" . $arrayPedido['pedidoDescricao'] . "',
            id_coleta=" . $arrayPedido['perfilId'] . "
        WHERE 
            id_pedido=" . $arrayPedido['pedidoId'] ;

        $this -> resultado = $this -> Conn -> query($sql);
    
    }

    public function FinalizarPedido($pedidoId){

        $sql = "UPDATE tb_pedido 
                SET status = 9 
                WHERE id_pedido = $pedidoId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function CancelarPedido($pedidoId){

        $sql = "UPDATE tb_pedido 
                SET status = 8 
                WHERE id_pedido = $pedidoId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function RemovePedido($pedidoId){

        $sql = "DELETE FROM tb_pedido 
                WHERE id_pedido = $pedidoId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function ObtemConsulta(){
        return $this -> resultado;
    }

}
?>