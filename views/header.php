<!doctype html>
<html lang="pt">
    <head>
    
        <title>
            <?php 
                if(_MODO_DE_TRABALHO=='dev'){ 
                    echo "DEV ";
                } 
                echo _PROJETO_TITULO ;
            ?>
        </title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?= _PROJETO_TITULO ?>">
        <meta name="author" content=<?= _PROJETO_COPYRIGHT ?>>
		<meta name="robots" content="index,nofollow,noimageindex">
		<meta name="robots" content="noarchive">
		<meta name="googlebot" content="index,nofollow,noimageindex">
		<meta name="googlebot" content="noarchive">
		<meta http-equiv="Expires" content="0">
		<META http-equiv="Pragma" content="no-cache">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<script src="https://kit.fontawesome.com/f80b1e2089.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/css/estilo_proprio.css">

        <!--[if lt IE 9]>
        <script src="assets/js/html5.js"></script>
        <![endif]-->

    </head>

    <body>
        <header>
            <?php 
                require_once("views/menuPrincipal.php");
            ?>
        </header>
        <aside>
        </aside>
        <section class="mt-5 pt-5 pb-5">