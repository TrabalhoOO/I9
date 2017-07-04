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
                <h1><i class="fa fa-shopping-cart"></i> Vendas</h1>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Vendas</h3>
                </div>
                

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SESSION['tipo'] == 'gerente') {
                            $sql = "SELECT id_pedido,data,valorTotal,nome,fechada FROM pedido  "
                                    . "inner join cliente on(pedido.id_cli = cliente.FK_pessoa) "
                                    . "inner join pessoa on(cliente.FK_pessoa = pessoa.id_pessoa) where fechada = 1 ";

                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->execute();
                                while ($pedido = $stmt->fetchObject()) {
                                    $vendaData = strtotime($pedido->data);
                                    ?>
                                    <tr>
                                        <td><?php echo $pedido->id_pedido; ?></td>
                                        <td><?php echo date('d/m/Y', $vendaData); ?></td>
                                        <td><?php echo $pedido->nome; ?></td>
                                        <td>R$ <?php echo number_format($pedido->valorTotal, 2, ",", "."); ?></td>
                                        <td>
                                            <a href="venda-detalhes.php?idvenda=<?php echo $pedido->id_pedido; ?>" title="Detalhes da venda"><i class="fa fa-align-justify fa-lg"></i></a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php
                    }
                } elseif ($_SESSION['tipo'] == 'cliente') {
                    $sql = "SELECT id_pedido,data,valorTotal,nome,fechada FROM pedido  "
                            . "inner join cliente on(pedido.id_cli = cliente.FK_pessoa) "
                            . "inner join pessoa on(cliente.FK_pessoa = pessoa.id_pessoa) where fechada = 1"
                            . " and cliente.FK_pessoa = :cliente ";

                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bindParam(':cliente',$_SESSION['id_pessoa']);
                        $stmt->execute();
                        while ($pedido = $stmt->fetchObject()) {
                            $vendaData = strtotime($pedido->data);
                            ?>
                            <tr>
                                <td><?php echo $pedido->id_pedido; ?></td>
                                <td><?php echo date('d/m/Y', $vendaData); ?></td>
                                <td><?php echo $pedido->nome; ?></td>
                                <td>R$ <?php echo number_format($pedido->valorTotal, 2, ",", "."); ?></td>
                                <td>
                                    <a href="venda-detalhes.php?idvenda=<?php echo $pedido->id_pedido; ?>" title="Detalhes da venda"><i class="fa fa-align-justify fa-lg"></i></a>

                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        </table>
                        <?php
                    }
                }
                ?>
            </div>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>