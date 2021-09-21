<?php
    global $diretorios;
?>

<div class="container">
        
    <h2>Atualização de Perfil</h2>
    <p>Altere as informações desejadas, no formulário abaixo, e clique no botão Salvar.</p>
    <form action="?c=c&a=ua" method=POST enctype='multipart/form-data'>
        <div class="form-group">
            <label for="perfilId">Id:</label>
            <input type="text" class="form-control" name="perfilId" 
            value="<?=$id_perfil?>" 
            readonly>
        </div>
        <div class="form-group">
            <label for="perfilRazaoSocial">Razão Social:</label>
            <input type="text" class="form-control" name="perfilRazaoSocial" 
            value="<?=$arrayPerfil['nome_razaosocial']?>">
        </div>
        <div class="form-group">
            <label for="perfilRperfilNomeazaoSocial">Título ou Nome Fantasia:</label>
            <input type="text" class="form-control" name="perfilNome" 
            value="<?=$arrayPerfil['nome_fantasia']?>">
        </div>
        <div class="form-group">
            <label for="perfilCNPJ">CNPJ:</label>
            <input type="num" class="form-control" name="perfilCNPJ" 
            placeholder="00.000.000/0000-00" pattern="[0-9]{14}" title="14 dígitos, somente números" 
            required autofocus value=<?=$arrayPerfil['cnpj']?>>
        </div>
        <div class="form-group">
            <label for="perfilTipo">Tipo do perfil:</label>
            <select class="form-control" name="perfilTipo" id="perfilTipo" required>
                <option value=""></option>
                <?php
                $Indice=0;
                foreach ( $this -> ConfigSis['tipos_de_cadastro'] as $tipo ) {
                    echo "<option value=$tipo";
                    if($Indice==$arrayPerfil['tipo_cadastro']){
                        echo " selected ";
                    }
                    echo ">$tipo</option>";
                    $Indice++;
                }
                ?>
        </div>
        </select>
        <div class="form-group">
            <label for="perfilEmail">Correio eletrônico:</label>
            <input type="email" class="form-control" name="perfilEmail" 
            value="<?=$arrayPerfil['email']?>">
        </div>
        <div class="form-group">
            <label for="perfilTelefone">Telefone:</label>
            <input type="tel" class="form-control" name="perfilTelefone" 
            placeholder="Exemplo: 51993988725" pattern="[0-9]{10,11}" required 
            alt="Campo do primeiro telefone" title="Até 11 dígitos, com DDD, sendo somente números." 
            value="<?=$arrayPerfil['telefone']?>">
        </div>
        <div class="form-group">
            <label for="perfilEndereco">Endereço:</label>
            <input type="text" class="form-control" name="perfilEndereco" 
            value="<?=$arrayPerfil['endereco']?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
    
</div>