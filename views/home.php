<div class="container-fluid">

    <div class="row m-0 p-0">
        <div class="col col-sm m-1 p-3">
            <b>
                <?php 
                    echo $this -> sisConfig['tipos_de_cadastro'][ $cadastroPerfil['tipo_cadastro'] ];
                ?>
            </b>
            <h3>
                <b>
                    <?= strtoupper( $cadastroPerfil['nome_fantasia'] ) ?>
                </b>
            </h3>
            <p>
                <b>
                    <?= strtoupper( $cadastroPerfil['nome_razaosocial']) ?>
                </b>
                <br>
                CNPJ: <?= $cadastroPerfil['cnpj'] ?>
                <br>
                Endereço: <?= $cadastroPerfil['endereco'] ?>
                <br>
                Telefone: <?= $cadastroPerfil['telefone'] ?>
                <br>
                E-Mail: <?= $cadastroPerfil['email'] ?>
                <br>
                <a href="?c=c&a=u&id=<?=$cadastroPerfil['id_perfil']?>">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Atualizar o perfil
                </a>
            </p>
        </div>
    </div>

    <div class="row m-0 p-0">
        <div class="col col-sm m-1 p-3">
            <b>
                Pedidos de Coleta:
            </b>
            <br>
            <p class="text-secondary">Exibir, aqui, lista dos recentes pedidos de coleta postados</p>
        </div>
    </div>

</div>
