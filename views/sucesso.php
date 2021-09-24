   <div class="container">
      <div class="row-sm">
         <div class="col-sm">
            <center>
            <h1>
               <?php
               if( $cTituloDoSucesso != null ){
                  echo $cTituloDoSucesso;
               }else{
                  echo "Sucesso!";
               }
               ?>
            </h1>
            <br><br>

            <h5>Muito bem, 
            <?php
            if( $cMensagemDeSucesso != null ){
               echo $cMensagemDeSucesso;
            }else{
               echo "deu tudo certo!";
            }
            ?>
            </h5>
            
            <br><br>
            
            Clique ou toque sobre o botão Continuar, para prosseguir.

            <br><br>

            <a class="btn btn-lg btn-primary" href="
               <?php
                  if( $cCaminhoDoBotaoSucesso != null ){
                     echo $cCaminhoDoBotaoSucesso;
                  }else{
                     echo ".";
                  }
               ?>
            ">
               Continuar
            </a>

            <br><br>
            </center>
         </div> 
      </div>
   </div>