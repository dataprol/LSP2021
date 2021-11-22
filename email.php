<?php

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\PHPMailer;

    require_once('PHPMailer/src/Exception.php');
    require_once('PHPMailer/src/SMTP.php');
    require_once('PHPMailer/src/PHPMailer.php');

    
    function EmailEnviar($cMailDestinatario,$cMailAssunto,$cTituloMensagem,$cMailmensagem,$Config){
        
        $objetomail = new PHPMailer;
    
        // Prepara mensagem de e-Mail
        $cMailCharSet = 'UTF-8';
        $cMailHeaders = '';
        $cMailOrigem = 'suporte@lsp.provisorio.ws';
        $cMailNomeOrigem = _PROJETO_TITULO;
        $cMailResposta = 'suporte@lsp.provisorio.ws';
        $cMailNomeResposta = _PROJETO_TITULO;
        $cMailDestino = $cMailDestinatario[0];
        $cMailNomeDestino = $cMailDestinatario[1];
        //$cMailAssunto = 'Recuperação de Senha para ' . $arrayUsuarios["login"] ;
        $cMailmensagem = '
        <html lang="pt">
            <meta charset="' . mb_strtolower($cMailCharSet) . '">
            <meta name="author" content="' . _PROJETO_AUTORIA . '">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, shrink-to-fit=no">
            <head>
                <title>'.$cTituloMensagem.'</title>
            </head>
            <body>' . $cMailmensagem . '</body>
        </html>
        ';         
    
        $objetomail->setLanguage('br');                             // Habilita as saídas de erro em Português
        $objetomail->isHTML(true);                                  // Configura o formato do email como HTML
        $objetomail->isSMTP();                                      // Configura o disparo como SMTP
        $objetomail->CharSet='UTF-8';                               // Habilita o envio do email como 'UTF-8'
        $objetomail->Subject = $cMailAssunto;
        $objetomail->Body    = $cMailmensagem;
        $objetomail->AltBody = strip_tags( $cMailmensagem );
        $objetomail->Host = $Config['hostname'];         // Especifica o enderço do servidor SMTP da Locaweb
        $objetomail->Username = $Config['username'];     // Usuário do SMTP
        $objetomail->Password = $Config['password'];     // Senha do SMTP
        $objetomail->SMTPAuth = true;                               // Habilita a autenticação SMTP
        $objetomail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Habilita criptografia TLS | 'ssl' também é possível
        $objetomail->Port = 587;                                    // Porta TCP para a conexão
        $objetomail->From = $cMailOrigem;                           // Endereço previamente verificado no painel do SMTP
        $objetomail->FromName = $cMailNomeResposta;                 // Nome no remetente
        $objetomail->addAddress($cMailDestino, $cMailNomeDestino);  // Acrescente um destinatário
        $objetomail->addReplyTo($cMailOrigem, $cMailNomeResposta);  // Endereço para respostas
    
        // Envia
        return $objetomail->send();

    }

?>