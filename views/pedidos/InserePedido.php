<div class="container">

    <h2>Cadastro de Pedido de Coleta</h2>

    <p>
    No formulário a seguir, preencha os campos solicitados e, para confirmar, clique em Salvar.<br>
    </p>
    <br>

    <form action="?c=p&a=ia" method="post" enctype="multipart/form-data" name="form1" id="form1">

        <div class="form-group">
            <label for="pedidoDescricao">Descrição:</label>
            <input type="text" class="form-control" name="pedidoDescricao" id="pedidoDescricao" 
            placeholder="exemplo: feijão com arroz" required
            autofocus>
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
        
        <!-- Botões -->
        <button type="submit" class="btn btn-success">Salvar</button>
        &nbsp;&nbsp;
        <a class="btn btn-danger" href="#" onclick="window.history.back();">
            Voltar
        </a>

    </form>

</div>