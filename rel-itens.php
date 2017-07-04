<?php
require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

if ($_POST) {
    $sql = "select nome,quantidade,valorUnitario, quantidade*valorUnitario as total, data from itempedido "
            . "inner join pedido on (itempedido.FK_pedido = pedido.id_pedido) "
            . "inner join produto_estoque on(itempedido.FK_estoqGlobal = produto_estoque.id_produto) where id_cli = :id_cli "
            . "and data between :inicio and :fim and pedido.fechada =".VENDA_FECHADA." order by data";

    if (isset($_POST['inicio']) && isset($_POST['fim'])) {
        $inicio = $_POST['inicio'];
        $fim = $_POST['fim'];
        $cliente = $_SESSION['id_pessoa'];
    if ($stmt = $conn->prepare($sql)) {

            $stmt->bindParam(":id_cli", $cliente);
            $stmt->bindParam(":inicio", $inicio);
            $stmt->bindParam(":fim", $fim);
        
        $stmt->execute();
    }
    }
}
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Produtos Comprados</title>

            <?php headCss(); ?>
        </head>
        <body>

            <?php
            criarNav($_SESSION['tipo']);
            ?>
            <div class="container">

                <div class="page-header">
                    <h1><i class="fa fa-credit-card"></i> Produtos Comprados</h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Produtos Comprados</h3>
                    </div>

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                                <th>Total Pago</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($produto = $stmt->fetchObject()) {
                                $vendaData = strtotime($produto->data);
                                ?>
                                <tr class="linha">
                                    <td><?php echo $produto->nome; ?></td>
                                    <td><?php echo $produto->quantidade; ?></td>
                                    <td><?php echo number_format($produto->valorUnitario, 2, ',', '.') ?></td>
                                    <td><?php echo number_format($produto->total, 2, ',', '.') ?></td>
                                    <td><?php echo date('d/m/Y', $vendaData); ?></td>
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