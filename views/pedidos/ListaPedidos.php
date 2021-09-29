<div class="container-fluid">

    <h2>Lista de Pedidos de Coleta</h2>
    <b>
    <div class="row">
        <div class="col col-sm-1">Código</div>
        <div class="col col-sm-2">Descricao</div>
        <div class="col col-sm-2">Cadastrado</div>
        <div class="col col-sm-2">Prazo</div>
        <div class="col col-sm-1">Situação</div>
    </div>
    </b>
    <hr>
    <?php
    foreach( $arrayPedidos as $pedido ){
    ?>
        <div class="row ">
            <div class="col col-sm-1 d-flex flex-sm-nowrap"><?= $pedido["id_pedido"] ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["descricao"] ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["dt_cadastro"] ?></div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= $pedido["dt_limite"] ?></div>
            <div class="col col-sm-auto d-flex flex-sm-nowrap"><?= $pedido["status"] ?></div>
        </div>
        <div class="row">
            <div class="col col-sm-auto">
<!--                 
                <?php                
                if(verificaNivelAcesso(9)){ ?>
                    <a class="btn btn-sm btn-primary" href="?c=p&a=u&id=<?=$pedido['id_pedido']?>">
                        Editar
                    </a>
                <?php 
                } 
                if( verificaNivelAcesso(9) and $pedido['id_pedido'] != $_SESSION['id_pedido'] ){ ?>
                    <button type="button" class="btn btn-sm btn-danger" 
                    data-toggle="modal" 
                    data-target="#modalExclusao" 
                    data-nome="<?=$pedido['id_pedido']?>" 
                    data-codigo="<?=$pedido['id_pedido']?>"
                    >
                        Remover
                    </button>
                <?php 
                } ?>
 -->                
            </div>
        </div>
        <hr>

    <?php
    }
    ?>
    
    <center>
    <a class="btn btn-success" href="?c=p&a=i">Adicionar</a>
    </center>

    <!-- Janela Modal Exclusão -->
    <div id="modalExclusao" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="pedido-nome" disabled value="Pedido">
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" href="#" id="botaoSim">Sim</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>    
    <!-- fim Janela Modal Exclusão -->

    <script>
        $('#modalExclusao').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var nome = button.data('nome') // Extract info from data-* attributes
            var codigo = button.data('codigo') // Extract info from data-* attributes
            var modal = $(this)
            modal.find('.modal-body input').val("Deseja, realmente, excluir o pedido " + nome + "?")
            document.getElementById("botaoSim").href = "?c=p&a=d&id=" + codigo
        })
    </script>

</div>