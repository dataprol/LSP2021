<div class="container">

    <?php
    if( !isset($_SESSION["usuarioNomeLogin"]) and !isset($_SESSION["id_perfil"]) ){
        echo("<h1>Acesso proibido</h1>");
        session_destroy();
    }else{
    ?>

    <h2>Cadastro de Pedido de Coleta</h2>

    <p>
    No formulário a seguir, preencha os campos solicitados e, para confirmar, clique em Salvar.<br>
    </p>
    <br>

    <form action="?c=p&a=ia" method="post" enctype="multipart/form-data" name="form1" id="form1" 
    onsubmit="return Validar(this);">

    <div class="form-group">
        <label for="pedidoDescricao">Descrição:</label>
        <input type="text" class="form-control" name="pedidoDescricao" id="pedidoDescricao" 
        placeholder="exemplo: feijão com arroz" required>
    </div>
    <div class="form-group">
        <label for="pedidoDataLimite">Prazo para retirada:</label>
        <input type="datetime-local" class="form-control" name="pedidoDataLimite" id="pedidoDataLimite"
        min="<?=date("Y-m-d H:i:s")?>" placeholder="exemplo: 2021-01-31 23:59:59" required>
    </div>
    
    <!-- Campos ocultos -->
    <input type="number" class="form-control" name="perfilId" id="perfilId" 
    value=<?=$_SESSION['id_perfil']?>
    hidden>
    
    <button type="submit" class="btn btn-success">Salvar</button>

    </form>

    <?php
    }
    ?>

    <script language="javascript">

        function Validar( form1 ){

        }
        
    </script> 

</div>