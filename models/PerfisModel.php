<?php
class PerfisModel{
    
    var $resultado;
    var $Conn;

    function __construct($bd){
        
        require_once("bd/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> openConnect($bd);
        $this -> Conn = $oConexao -> getConnect();

    }

    public function ConsultaPerfil($perfilId){

        $sql = "SELECT 
        `cnpj`,
        `tipo_cadastro`,
        `nome_razaosocial`,
        `nome_fantasia`,
        `email`,
        `telefone`,
        `endereco` 
        FROM tb_perfil 
        WHERE id_perfil = $perfilId ";

        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function InserePerfil($arrayPerfil){

        $sql = "INSERT INTO `tb_perfil`
        (
        `cnpj`,
        `tipo_cadastro`,
        `nome_razaosocial`,
        `nome_fantasia`,
        `email`,
        `telefone`,
        `endereco`
        )
        VALUE(
        '" . $arrayPerfil['perfilCNPJ'] . "', 
        "  . $arrayPerfil['perfilTipo'] . ", 
        '" . $arrayPerfil['perfilRazaoSocial'] . "', 
        '" . $arrayPerfil['perfilNome'] . "', 
        '" . $arrayPerfil['perfilEmail'] . "', 
        '" . $arrayPerfil['perfilTelefone'] . "', 
        '" . $arrayPerfil['perfilEndereco'] . "'
        );";

        $this -> Conn -> query($sql);
        
        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function InsereUsuario( $arrayUsuarios ){

        $sql = "INSERT INTO tb_usuario
        (
        login,
        email,
        senha,
        nivel,
        telefoneCelular,
        fk_id_perfil_usuario
        ) 
        VALUE(
        '" . $arrayUsuarios['usuarioLogin'] . "', 
        '" . $arrayUsuarios['usuarioEmail'] . "', 
        '" . $arrayUsuarios['usuarioSenha'] . "', 
        "  . $arrayUsuarios['usuarioNivel'] . ", 
        '" . $arrayUsuarios['usuarioTelefoneCelular'] . "', 
        "  . $arrayUsuarios['perfilId'] . "
        );";

        $this -> Conn -> query($sql);

        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function AtualizaPerfil($arrayPerfil){
        
        $sql = "UPDATE `tb_perfil`
        SET
        `cnpj` = '" . $arrayPerfil['perfilCNPJ'] . "',
        `tipo_cadastro` = "  . $arrayPerfil['perfilTipo'] . ",
        `nome_razaosocial` = '" . $arrayPerfil['perfilRazaoSocial'] . "',
        `nome_fantasia` = '" . $arrayPerfil['perfilNome'] . "',
        `email` = '" . $arrayPerfil['perfilEmail'] . "',
        `telefone` = '" . $arrayPerfil['perfilTelefone'] . "',
        `endereco` = '" . $arrayPerfil['perfilEndereco'] . "' 
        WHERE 
            id_perfil=" . $arrayPerfil['perfilId'] ;

        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function ObtemConsulta(){
        return $this -> resultado;
    }

}
?>