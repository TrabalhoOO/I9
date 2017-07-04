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
        <title>Novo Fornecimento</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">
            
            <div class="page-header">
                <h1><i class="fa fa-shopping-cart"></i> Fornecimento</h1>
            </div>
            <?php
            if (isset($_SESSION['fornecimento']) && $_SESSION['fornecimento'] == TRUE) {
                $msg = 'Produto ' . $_SESSION['produto_fornecido'] . ' fornecido com sucesso';
                msgHtml($msg, "success");
            }
            $_SESSION['fornecimento'] = false;
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Fornecimentos</h3>
                </div>


                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Fornecedor</th>
                            <th>Produto</th>
                            <th>Quantidade</th>                            
                            <th>Custo Unitário</th>
                            <th>Custo Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SESSION['tipo'] == 'gerente') {
                            $sql = "SELECT f.id_fornecimento, f.dataEntrada, p.nome as nomePessoa, 
                                    pe.nome as nomeProduto, f.quantidade, f.custoUnitario, 
                                    f.quantidade * f.custoUnitario as custoTotal 
                                    from fornecimento f
                                    INNER JOIN fornecedor fr on f.FK_fornecedor = fr.FK_pessoa
                                    INNER JOIN pessoa p on fr.FK_pessoa = p.id_pessoa
                                    INNER JOIN produto_estoque pe on f.FK_estoqGlobal = pe.id_produto order by f.dataEntrada";

                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->execute();
                                while ($pedido = $stmt->fetchObject()) {
                                    $vendaData = strtotime($pedido->dataEntrada);
                                    ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', $vendaData); ?></td>
                                        <td><?php echo $pedido->nomePessoa; ?></td>
                                        <td><?php echo $pedido->nomeProduto; ?></td>
                                        <td><?php echo $pedido->quantidade; ?></td>                                        
                                        <td>R$ <?php echo number_format($pedido->custoUnitario, 2, ",", "."); ?></td>
                                        <td>R$ <?php echo number_format($pedido->custoTotal, 2, ",", "."); ?></td>                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php
                    }
                }
                ?>
            </div>
            <button type="button" class="btn btn-success hidden-print" onclick="window.print()"><span class="fa fa-print fa-lg"></span> Imprimir</button>
       
        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>