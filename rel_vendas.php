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
        <title>Vendas</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-user fa-lg"></i> Relatório de Produtos Comprados</h1>
            </div>

            <form role="form "class="form-horizontal"  method="post" action="rel-itens.php">
                <div class="row ">

                    <div class="form-group">
                        <div class="col-lg-4">
                        <label for="finicio">De: </label>
                        
                        <input type="date" id="finicio" name="inicio" class="form-control" required="true">
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="col-lg-4">
                        <label for="fim">Até:  </label>
                        <input type="date" id="fim" name="fim" class="form-control"  required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-file fa-lg"></span> Gerar Relatório</button>
                        <button type="reset" class="btn btn-danger">Cancelar</button>
                        </div>
                    </div>
                </div>

            </form>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>