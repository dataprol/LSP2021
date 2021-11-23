<div class="container-fluid">

    <h2>Lista de Suas Doações</h2>
    <b>
    <div class="row">
        <!-- <div class="col col-sm-1">Código</div> -->
        <div class="col col-sm-1">Situação</div>
        <div class="col col-sm-2">Descricao</div>
        <!-- <div class="col col-sm-2">Cadastrado</div> -->
        <div class="col col-sm-2">Prazo</div>
    </div>
    </b>
    <hr>
    <?php
    foreach( $arrayPedidos as $pedido ){
    ?>
        <div class="row">
            <!-- <div class="col col-sm-1 d-flex flex-sm-nowrap"><?= $pedido["id_pedido"] ?></div> -->
            <div class="col col-sm-1 d-flex flex-sm-nowrap
                <?php
                    if($pedido["status"]==0){
                        echo "text-primary";
                    }
                    if($pedido["status"]==1){
                        echo "text-success";
                    }
                    if($pedido["status"]==8){
                        echo "text-danger";
                    }
                    if($pedido["status"]==9){
                        echo "text-dark";
                    }
                ?>
            ">
                <b><?= array_search($pedido["status"],_PEDIDOS_SITUACOES) ?></b>
            </div>
            <div class="col col-sm-2 d-flex flex-sm-nowrap"><b><?= $pedido["descricao"] ?></b></div>
            <!-- <div class="col col-sm-2 d-flex flex-sm-nowrap"><?= strftime( _FMT_DATA_HORA, strtotime( $pedido["dt_cadastro"] ) ) ?></div> -->
            <div class="col col-sm-2 d-flex flex-sm-nowrap
                <?php
                    if( $pedido["dt_limite"] < date('Y-m-d H:i:s') ){
                        echo " text-danger";
                    }
                ?>
            ">
                <?= strftime( _FMT_DATA_HORA, strtotime( $pedido["dt_limite"] ) ) ?>
            </div>
            <br>
        </div>
        
        <?php if( $pedido["status"] == 1 ){ ?>
        
        <div class="row text-success" title="ONG que reservou essa doação.">
            <div class="pl-3 m-1">
                <i class="fas fa-id-card"></i>
                    <b>Quem reservou:</b>
                        <?= $pedido["nome_fantasia"] ?> 
                        <!-- <b>Razão Social:</b> 
                            <?= $pedido["nome_razaosocial"] ?> -->
                            
                                <b>CNPJ:</b> 
                                <a href="http://servicos.receita.fazenda.gov.br/Servicos/cnpjreva/Cnpjreva_Solicitacao.asp?cnpj=<?= $pedido["cnpj"] ?>" 
                                target="_blank">
                                    <?= preg_replace(  '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $pedido['cnpj'] ) ?>
                                </a>
            </div>
            <div class="pl-3 m-1">
                <i class="fa fa-solid fa-map"></i>
                    <b></b>
                        <a href="https://www.google.com/maps/dir/?api=1&origin=<?= urlencode($pedido["endereco"]) ?>&destination=<?= urlencode($_SESSION['endereco_perfil']) ?>" target=_blank>
                            <?= $pedido["endereco"] ?>
                        </a>
            </div>
            <div class="pl-3 m-1">
                <i class="fa fa-solid fa-envelope"></i> 
                    <b></b>
                        <a href="mailto:<?= $pedido["email"] ?>">
                            <?= $pedido["email"] ?>
                        </a>
            </div>
            <div class="pl-3 m-1">
                <i class="fa fa-solid fa-phone"></i> 
                    <b></b>
                        <a href="tel:+55<?=$pedido["telefone"]?>">
                            <?= $pedido["telefone"] ?>
                        </a>
            </div>
        </div>
        
        <?php } ?>
        
        <div class="row">
            <div class="col col-sm-auto">
                
                <?php                
                if( verificaNivelAcesso("Administrativo") and $pedido['status'] == 1){ ?>
                    <button type="button" class="btn btn-sm btn-success" 
                    data-toggle="modal" 
                    data-target="#modalSimNao" 
                    data-desc="Confirma que a doação de <?=$pedido['descricao']?> foi, realmente, coletada?" 
                    data-link="?c=p&a=fin&id=<?=$pedido['id_pedido']?>"
                    >
                        Finalizar
                    </button>
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