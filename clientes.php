<?php
require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

if ($_POST) {
    $sql = "select * from pessoa as p inner join cliente as c on p.id_pessoa = c.fk_pessoa inner join cidade as cid on c.FK_cidade = cid.id_Cidade ";

    if (isset($_POST['estado']) && $_POST['estado'] != 'Todos') {
        $sql .= "WHERE estado = :estado ";
    }
    if (isset($_POST['ordenar']) && $_POST['ordenar'] == 'cliente') {
        $sql .= "Order BY nome ";
    }

    if (isset($_POST['ordenar']) && $_POST['ordenar'] == 'estado') {
        $sql .= "Order BY cidade,estado";
    }
    if ($stmt = $conn->prepare($sql)) {

        if (isset($_POST['estado']) && $_POST['estado'] != 'Todos') {
            $stmt->bindParam(":estado", $_POST['estado']);
        }
        $stmt->execute();
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Clientes</title>

            <?php headCss(); ?>
        </head>
        <body>

            <?php
            criarNav($_SESSION['tipo']);
            ?>
            <div class="container">

                <div class="page-header">
                    <h1><i class="fa fa-user"></i> Cliente</h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Clientes</h3>
                    </div>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Ponto de Referência</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($cliente = $stmt->fetchObject()) {
                                ?>
                                <tr class="linha">
                                    <td><?php echo $cliente->id_pessoa; ?></td>
                                    <td><?php echo $cliente->nome; ?></td>
                                    <td><?php echo $cliente->telefone; ?></td>
                                    <td><?php echo $cliente->email ?></td>
                                    <td><?php echo $cliente->ponto_ref_entrega ?></td>
                                    <td><?php echo $cliente->cidade ?></td>
                                    <td><?php echo Estados($cliente->estado) ?></td>
                                </tr><?php
                    }
                }
                        ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success" onclick="window.print()"><span class="fa fa-print fa-lg"></span> Imprimir</button>
        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>