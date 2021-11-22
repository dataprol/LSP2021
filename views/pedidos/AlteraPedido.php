<div class="container">
        
    <h2>Atualização de Pedido de Coleta</h2>

    <p>
    No formulário a seguir, preencha os campos solicitados e, para confirmar, clique em Salvar.<br>
    </p>
    <br>

    <form action="?c=p&a=ua" method=POST enctype='multipart/form-data' name="form1" id="form1">

        <div class="form-group">
            <label for="pedidoDescricao">Descrição:</label>
            <input type="text" class="form-control" name="pedidoDescricao" id="pedidoDescricao" 
            placeholder="exemplo: feijão com arroz" required
            value="<?=$arrayPedido['descricao']?>" autofocus>
        </div>
        <div class="form-group">
            <label for="pedidoDataLimite">Prazo para retirada:</label>
            <input type="datetime-local" class="form-control" name="pedidoDataLimite" id="pedidoDataLimite"
            min="<?=date("Y-m-d H:i:s")?>" placeholder="exemplo: 2021-12-31 23:59:59"
            value="<?=DateTime::createFromFormat( 'Y-m-d H:i:s', $arrayPedido['dt_limite'] ) -> format('Y-m-d H:i:s')?>" 
            required>
        </div>
        
        <!-- Campos ocultos -->
        <input type="number" class="form-control" name="pedidoId" id="pedidoId" 
        value=<?=$arrayPedido['id_pedido']?>
        hidden>
        
        <button type="submit" class="btn btn-success">Salvar</button>
        &nbsp;&nbsp;
        <a class="btn btn-danger" href="#" onclick="window.history.back();">
            Voltar
        </a>
    
    </form>
    
</div>