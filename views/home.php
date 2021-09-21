<div class="container-fluid">

    <div class="row m-0 p-0">
        <div class="col col-sm m-1 p-3">
            <b>
                <?php 
                    echo $this -> ConfigSis['tipos_de_cadastro'][ $cadastroPerfil['tipo_cadastro'] ];
                ?>
            </b>
            <h3>
                <b>
                    <?= strtoupper( $cadastroPerfil['nome_razaosocial'] ) ?>
                </b>
            </h3>
            <a href="?c=c&a=u&id=<?=$cadastroPerfil['id_perfil']?>">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                Atualizar o perfil
            </a>
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
