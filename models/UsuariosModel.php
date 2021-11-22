<?php

class usuariosModel{

    var $resultado;
    var $Conn;

    function __construct($bd){
        
        require_once("bd/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> openConnect($bd);
        $this -> Conn = $oConexao -> getConnect();

    }

    public function consultaUsuarioId( $id_usuario ){
        
        $sql = "SELECT * FROM tb_usuario WHERE id_usuario='$id_usuario'";
        
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function consultaUsuarioLogin( $login ){

        $sql = "SELECT * FROM tb_usuario WHERE login='" . $login . "'";
        
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function consultaUsuarioEmail( $email ){
        
        $sql = "SELECT * FROM tb_usuario WHERE email='" . $email . "'";
        
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function InsereUsuario( $arrayUsuarios ){

        $sql = "INSERT INTO tb_usuario(login,email,senha,nivel,telefoneCelular,fk_id_perfil_usuario) 
        VALUE(
        '" . $arrayUsuarios['usuarioLogin'] . "', 
        '" . $arrayUsuarios['usuarioEmail'] . "', 
        '" . $arrayUsuarios['usuarioSenha'] . "', 
        "  . $arrayUsuarios['usuarioNivel'] . ", 
        '" . $arrayUsuarios['usuarioTelefoneCelular'] . "', 
        "  . $arrayUsuarios['perfilId'] . ");
        ";

        $this -> Conn -> query($sql);

        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function AtualizaUsuario($arrayUsuarios){

        $sql = "UPDATE tb_usuario 
        SET 
            email='" . $arrayUsuarios['usuarioEmail'] . "', 
            fk_id_perfil_usuario=" . $arrayUsuarios['usuarioPerfilId'] . ", 
            telefoneCelular='" . $arrayUsuarios['usuarioTelefoneCelular'] . "', 
            nivel=" . $arrayUsuarios['usuarioNivel'] . " 
        WHERE 
            id_usuario=" . $arrayUsuarios['usuarioId'] ;

        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function atualizaSenhaUsuario($arrayUsuarios){

        $sql = "UPDATE tb_usuario 
        SET senha='" . $arrayUsuarios['senha'] . "' 
        WHERE id_usuario=" . $arrayUsuarios['id_usuario'] ;

        $this -> resultado = $this -> Conn -> query($sql);
        
    }

    public function ConsultaPerfil($perfilId){

        $sql = "SELECT * 
        FROM tb_perfil 
        WHERE id_perfil = $perfilId";

        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function ListaUsuarios($perfilId){
        
        $sql = "SELECT * FROM tb_usuario 
                WHERE fk_id_perfil_usuario = $perfilId
                LIMIT 50";
                //ORDER BY usuarioNome LIMIT 25";
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function RemoveUsuario($usuarioId){

        $sql = "DELETE FROM tb_usuario 
                WHERE id_usuario = $usuarioId ";

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function ObtemConsulta(){

        return $this -> resultado;

    }
}

?>