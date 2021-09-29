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
                <h4>
                Recentes Pedidos de Coleta
                </h4>
            </b>
            <b>
            <div class="row">
                <div class="col col-sm-1">Código</div>
                <div class="col col-sm-2">Descricao</div>
                <div class="col col-sm-2">Prazo</div>
            </div>
            </b>
            <hr>
            <?php
            foreach( $arrayPedidos as $pedido ){
            ?>
                <div class="row ">
                    <div class="col col-sm-1 d-flex flex-sm-nowrap"><?= $pedido["id_pedido"] ?></div>
                    <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["descricao"] ?></div>
                    <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["dt_limite"] ?></div>
                </div>
                <hr>
            <?php
            }
            ?>
            
                    
        </div>
    </div>

</div>
