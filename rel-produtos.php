<?php
require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

$idcategoria = 0;

$q = '';
if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Produtos</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>
        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-shopping-cart"></i> Produtos Em Baixa</h1>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Produtos</h3>
                </div>
               
                <table class="table table-striped table-hover">
                    <thead>

                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Custo</th>
                            <th>Valor Sugerido</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "Select id_produto, qntd_total, custo_final, valor_sugerido, preco_venda,nome,descricao from produto_estoque where qntd_total <= 5 order by nome ";


                        if ($stmt = $conn->prepare($sql)) {

                            $stmt->execute();
                            while ($produto = $stmt->fetchObject()) {
                                ?>
                                <tr class="linha">
                                    <td><?php echo $produto->nome; ?></td>
                                    <td><?php echo $produto->descricao; ?></td>
                                    <td class="quantidade"><?php echo $produto->qntd_total ?></td>
                                    <td><?php echo number_format($produto->custo_final, 2); ?></td>
                                    <td><?php echo number_format($produto->valor_sugerido, 2); ?></td>
                                    <td><?php echo number_format($produto->preco_venda, 2); ?></td>
                                </tr><?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success hidden-print" onclick="window.print()"><span class="fa fa-print fa-lg"></span> Imprimir</button>
       
        </div>
        
        <script src="./lib/jquery.js"></script>
        <script src="./lib/funcoes.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>