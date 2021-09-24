   <div class="container">
      <div class="row-sm">
         <div class="col-sm">

            <center>
            <h1>
               <?php
               if( $cTituloDoErro != null ){
                  echo $cTituloDoErro;
               }else{
                  echo "Poxa, que pena!";
               }
               ?>
            </h1>
            <br><br>

            <h5>Desculpe-nos, 
            <?php
            if( $cMensagemDeErro != null ){
               echo $cMensagemDeErro;
            }else{
               echo "encontramos alguma falha!";
            }
            ?>
            </h5>
            
            <br><br>
            
            Experimente retornar à tela anterior e tentar novamente.

            <br><br>

            <a class="btn btn-lg btn-primary" href="#" onclick="window.history.back();">
               Voltar
            </a>

            &nbsp;

            <a class="btn btn-lg btn-primary" href=".">
               Início
            </a>

            <br><br>
            </center>

         </div> 
      </div>
   </div>