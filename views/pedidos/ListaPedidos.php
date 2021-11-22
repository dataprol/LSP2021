<div class="container-fluid">

    <h2>Lista de Suas Doações</h2>
    <b>
    <div class="row">
        <div class="col col-sm-1">Código</div>
        <div class="col col-sm-1">Situação</div>
        <div class="col col-sm-2">Descricao</div>
        <div class="col col-sm-2">Cadastrado</div>
        <div class="col col-sm-2">Prazo</div>
    </div>
    </b>
    <hr>
    <?php
    foreach( $arrayPedidos as $pedido ){
    ?>
        <div class="row ">
            <div class="col col-sm-1 d-flex flex-sm-nowrap"><?= $pedido["id_pedido"] ?></div>
            <div class="col col-sm-auto d-flex flex-sm-nowrap"><?= array_search($pedido["status"],_PEDIDOS_SITUACOES) ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["descricao"] ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= strftime( _FMT_DATA_HORA, strtotime( $pedido["dt_cadastro"] ) ) ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= strftime( _FMT_DATA_HORA, strtotime( $pedido["dt_limite"] ) ) ?></div>
            <br>
        </div>
        <?php 
        if( $pedido["status"] == 1 ){
        ?>
            <div class="row ">
                <div class="col col-sm-12 d-flex flex-sm-nowrap">
                    <font color="gray">
                        <b>
                            <?= $pedido["nome_fantasia"] ?>
                            <br>
                            Endereço: <?= $pedido["endereco"] ?>
                            <br>
                            E-mail: <?= $pedido["email"] ?>
                            <br>
                            Telefone: <?= $pedido["telefone"] ?>
                        </b>
                    </font>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col col-sm-auto">
                
                <?php                
                if( verificaNivelAcesso("Administrativo") and $pedido['status'] == 1){ ?>
                    <a class="btn btn-sm btn-success" href="?c=p&a=fin&id=<?=$pedido['id_pedido']?>">
                        Finalizar
                    </a>
                <?php 
                } 
                if( verificaNivelAcesso("Administrativo") and $pedido['status'] == 0){ ?>
                    <a class="btn btn-sm btn-primary" href="?c=p&a=u&id=<?=$pedido['id_pedido']?>">
                        Editar
                    </a>
                <?php 
                } 
                if( verificaNivelAcesso("Administrativo") and $pedido['status'] == 0 ){ 
                ?>
                    <button type="button" class="btn btn-sm btn-warning" 
                    data-toggle="modal" 
                    data-target="#modalSimNao" 
                    data-desc="Deseja, realmente, cancelar a doação <?=$pedido['descricao']?>" 
                    data-link="?c=p&a=canc&id=<?=$pedido['id_pedido']?>"
                    >
                        Cancelar
                    </button>
                <?php 
                }
                if( verificaNivelAcesso("Administrativo") and $pedido['status'] == 8 ){ 
                ?>
                    <button type="button" class="btn btn-sm btn-danger" 
                    data-toggle="modal" 
                    data-target="#modalSimNao" 
                    data-desc="Deseja, realmente, excluir a doação <?=$pedido['descricao']?>" 
                    data-link="?c=p&a=d&id=<?=$pedido['id_pedido']?>"
                    >
                        Remover
                    </button>
                <?php 
                }
                ?>
                
            </div>
        </div>
        <hr>

    <?php
    }
    ?>
    
    <center>
    <a class="btn btn-primary" href="?c=p&a=i">Adicionar</a>
    </center>

    <!-- Janela Modal Aceitação -->
    <div id="modalSimNao" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group" id="descricao">
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="#" id="botaoSim">Sim</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>    
    <!-- fim Janela Modal Aceitação -->

    <script>
        
        $('#modalSimNao').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            document.getElementById("descricao").innerHTML = button.data('desc')
            document.getElementById("botaoSim").href = button.data('link')
        })

    </script>

</div>