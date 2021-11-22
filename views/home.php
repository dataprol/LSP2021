<!-- <script>
    
    var x = document.getElementsByTagName( "iframe" );
    var cGeo = obtemGeolocalizacao(x);
    
    alert(x.name);

    function obtemGeolocalizacao(x){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(obtemCoordenadas);
        } else {
            alert("Geolocalização não é suportada pelo seu software ou dispositivo.");
            cGeo = "<?= $cadastroPerfil["endereco"] ?>";
        }
    }

    function obtemCoordenadas(){
        return position.coords.latitude + "," + position.coords.longitude;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                x.innerHTML = "Usuário proibiu acesso à sua geolocalização."
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML = "Informação de geolocalização indisponível."
                break;
            case error.TIMEOUT:
                x.innerHTML = "A solicitação de geolocalização expirou."
                break;
            case error.UNKNOWN_ERROR:
                x.innerHTML = "Um erro desconhecido ocorreu."
                break;
        }
        cGeo = "<?= $cadastroPerfil["endereco"] ?>";
    }

</script>
 -->
<div class="container-fluid">

    <div class="m-3 p-2">
        <div class="nowrap ml-2 mr-2" style="float:left;">
            <b>
                <?= _TIPOS_DE_CADASTRO[ $cadastroPerfil['tipo_cadastro'] ] ?>
                <h3>
                    <b>
                        <?= strtoupper( $cadastroPerfil['nome_fantasia'] ) ?>
                    </b>
                </h3>
                <?= strtoupper( $cadastroPerfil['nome_razaosocial']) ?>
            </b>
        </div>
        <div class="nowrap ml-2 mt-3 mr-2" style="float:left;">
            <i class="fa fa-solid fa-industry"></i>
            CNPJ: <?= preg_replace(  '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cadastroPerfil['cnpj'] ) ?>
            <br>
            <i class="fa fa-solid fa-map"></i>
            Endereço: <?= $cadastroPerfil['endereco'] ?>
        </div>
        <div class="nowrap ml-2 mt-3">
            <i class="fa fa-solid fa-phone"></i>
            Telefone: <?= preg_replace(  '/(\d{2})(\d*)(\d{3})(\d{3})/', '($1) $2-$3-$4', $cadastroPerfil['telefone'] ) ?>
            <br>
            <i class="fa fa-solid fa-envelope" aria-hidden="true"></i>
            E-Mail: <?= $cadastroPerfil['email'] ?>
        </div>
        <div class="ml-4 mt-0 p-0">
            <?php
            if( verificaNivelAcesso("Gerencial") ){ ?>
                <br>
                <a href="?c=c&a=u&id=<?=$cadastroPerfil['id_perfil']?>">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    <b>Atualizar o perfil</b>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="row m-0 p-0">
        <div class="col col-sm-12 sm-nowrap m-1 p-3">
            <b>
                <h4>
                <?php 
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG){ 
                        echo "Doações Dispoíveis";
                    }else{
                        echo "Sua Doações Disponíveis";
                    }
                ?>
                </h4>
            </b>
            <br>
            <b>
            <div class="row">
                <div class="col col-sm-2">Prazo</div>
                <div class="col col-sm-1">Situação</div>
                <div class="col col-sm-2">Descrição</div>
                <?php if( $_SESSION['tipo_cadastro'] == _TIPO_ONG){ ?>
                    <div class="col col-sm-3">Onde</div>
                <?php } ?>
            </div>
            </b>
            <hr>
            <?php
            foreach( $arrayPedidos as $pedido ){
            ?>
                <div class="row ">
                    <div class="col col-sm-2 d-flex flex-sm-nowrap">
                        <?= strftime( _FMT_DATA_HORA, strtotime( $pedido["dt_limite"] ) ) ?>
                    </div>
                    <div class="col col-sm-auto d-flex flex-sm-nowrap"><?= array_search($pedido["status"],_PEDIDOS_SITUACOES) ?></div>
                    <div class="col col-sm-2 d-flex flex-sm-nowrap">
                    <p>
                    <?= $pedido["descricao"] ?>
                    <br>
                    <br>
                    <?php                          
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG and $pedido["status"] == 0 and verificaNivelAcesso("Administrativo") ){ ?>
                        <button type="button" class="btn btn-sm btn-success" 
                        data-toggle="modal" 
                        data-target="#modalSimNao" 
                        data-desc="Você aceita o compromisso de coletar a doação <?=$pedido["descricao"]?>?" 
                        data-link="?c=m&a=pedreserv&id=<?=$pedido["id_pedido"]?>"
                        >
                            Coletar
                        </button>
                    <?php 
                    }
                    if( $_SESSION['tipo_cadastro'] == _TIPO_ONG and $pedido["status"] == 1 and verificaNivelAcesso("Administrativo")  ){ ?>
                        <button type="button" class="btn btn-sm btn-danger" 
                        data-toggle="modal" 
                        data-target="#modalSimNao" 
                        data-desc="Deseja, realmente, desistir de coletar <?=$pedido['descricao']?>" 
                        data-link="?c=m&a=peddes&id=<?=$pedido['id_pedido']?>"
                        >
                            Desistir
                        </button>
                    <?php 
                    } 
                    ?>
                    </p>
                </div>

                    <?php if( $_SESSION['tipo_cadastro'] == _TIPO_ONG){ ?>
                    <div class="col col-sm-3">
                        <b><?= strtoupper($pedido["nome_razaosocial"]) ?></b>
                        <br>
                        <i class="fa fa-solid fa-map"></i>
                        <?= $pedido["endereco"] ?>
                        <br>
                        <i class="fa fa-solid fa-phone"></i>
                        <?= preg_replace(  '/(\d{2})(\d*)(\d{3})(\d{3})/', '($1) $2-$3-$4', $pedido["telefone"] )?>
                        <br>
                        <i class="fa fa-solid fa-envelope"></i>
                        <?= $pedido["email"] ?>
                    </div>
                    <?php } ?>
                    <?php if( $_SESSION['tipo_cadastro'] == _TIPO_ONG){ ?>
                        <!-- <a class="btn btn-primary" onClick="obtemGeolocalizacao();">
                            Atualizar Rota
                        </a> -->
                        <iframe width="400" height="300" data-destino="<?= $pedido["endereco"] ?>"
                        frameborder="0" style="border:0" name="mapaRota" id="mapaRota" 
                        src="https://www.google.com/maps/embed/v1/directions?key=<?= _GOOGLE_API_KEY ?>
                        &origin=<?= $cadastroPerfil["endereco"] ?>
                        &destination=<?= $pedido["endereco"] ?>"
                        allowfullscreen>
                            <p>Seu navegador não suporta o recurso iframe.</p>
                        </iframe>                        
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col col-sm-auto">
                        
                    </div>
                </div>
                <hr>
            <?php
            }
            ?>

        </div>
    </div>


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

<script>
</script>