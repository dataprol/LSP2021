<?php
    global $diretorios;
?>

<div class="container-fluid">

    <h2>Lista de Usuários</h2>
    <b>
    <div class="row">
        <div class="col col-sm-2">Login</div>
        <div class="col col-sm-1">Telefone</div>
        <div class="col col-sm-auto">Email</div>
    </div>
    </b>
    <hr>
    <?php
    foreach( $arrayUsuarios as $usuario ){
    ?>
        <div class="row ">
            <div class="col col-sm-2 d-flex flex-sm-nowrap">
                <?= $usuario["login"] ?>
            </div>
            <div class="col col-sm-1 d-flex flex-sm-nowrap"><?= $usuario["telefoneCelular"] ?></div>
            <div class="col col-sm-auto d-flex flex-sm-nowrap"><?= $usuario["email"] ?></div>
        </div>
        <div class="row">
            <div class="col col-sm-auto">
                
                <?php
                
                if(verificaNivelAcesso(9)){ 
                ?>
                    <a class="btn btn-sm btn-primary" href="?c=u&a=u&id=<?=$usuario['id_usuario']?>">
                        Editar
                    </a>
                <?php 
                }
    
                if( verificaNivelAcesso(9) and $usuario['id_usuario'] != $_SESSION['id_usuario'] ){
                ?>
                    <button type="button" class="btn btn-sm btn-danger" 
                    data-toggle="modal" 
                    data-target="#modalExclusao" 
                    data-nome="<?=$usuario['login']?>" 
                    data-codigo="<?=$usuario['id_usuario']?>"
                    >
                        Remover
                    </button>
                <?php 
                } 
                
                if( verificaNivelAcesso(5) and $usuario['id_usuario'] != $_SESSION['id_usuario'] ){ 
                ?>
                    <a class="btn btn-sm btn-warning" href="?c=u&a=r&id=<?=$usuario['id_usuario']?>" role="button">
                        Reiniciar senha
                    </a>
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
    <a class="btn btn-success" href="?c=u&a=i">Adicionar</a>
    </center>

    <!-- Janela Modal Exclusão -->
    <div id="modalExclusao" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="usuario-nome" disabled value="Usuario">
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
            modal.find('.modal-body input').val("Deseja, realmente, excluir " + nome + "?")
            document.getElementById("botaoSim").href = "?c=u&a=d&id=" + codigo
        })
    </script>

</div>