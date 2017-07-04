<?php
require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

    $sql = "select * from pessoa as p inner join fornecedor as f on p.id_pessoa = f.FK_pessoa";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->execute();
    }

    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Fornecedores</title>

            <?php headCss(); ?>
        </head>
        <body>

            <?php
            criarNav($_SESSION['tipo']);
            ?>
            <div class="container">

                <div class="page-header">
                    <h1><i class="fa fa-truck"></i> Fornecedores</h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Fornecedores</h3>
                    </div>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Telefone 1</th>
                                <th>Telefone 2</th>
                                <th>E-mail</th>
                                <th>CNPJ</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($fornecedor= $stmt->fetchObject()) {
                                ?>
                                <tr class="linha">
                                    <td><?php echo $fornecedor->nome; ?></td>
                                    <td><?php echo $fornecedor->telefone; ?></td>
                                    <td><?php if($fornecedor->telefone2 != "") {echo $fornecedor->telefone2;} else {echo "Não cadastrado";} ?></td>
                                    <td><?php if($fornecedor->telefone3 != "") {echo $fornecedor->telefone3;} else {echo "Não cadastrado";} ?></td>
                                    <td><?php echo $fornecedor->email ?></td>
                                    <td><?php echo $fornecedor->CNPJ ?></td>
                                </tr><?php
                    }
                
                        ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success hidden-print" onclick="window.print()"><span class="fa fa-print fa-lg"></span> Imprimir</button>
        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>