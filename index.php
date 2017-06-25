<?php
require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Vendas</title>

        <?php
        headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        criarConteudo($_SESSION['tipo']);
        
        ?>

        

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>