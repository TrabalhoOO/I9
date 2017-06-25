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
                <h1><i class="fa fa-headphones"></i> Produtos</h1>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Produtos</h3>
                </div>
                <div class="panel-body">
                    <form class="form-inline" role="form" method="get" action="">
                        <div class="form-group">
                            <label class="sr-only" for="fq">Pesquisa</label>
                            <input type="search" class="form-control" id="fq" name="q" placeholder="Pesquisa" value="<?php echo $q; ?>">
                        </div>
                        <button type="submit" class="btn btn-default">Pesquisar</button>
                    </form>
                </div>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Custo</th>
                            <th>Valor Sugerido</th>
                            <th>Preço</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "Select id_produto, qntd_total, custo_final, valor_sugerido, preco_venda,nome,descricao from produto_estoque ";

                        if ($q != '') {
                            $sql .= "WHERE nome like :id_produto ";
                        }
                       
                        if ($stmt = $conn->prepare($sql)) {

                            if ($q != null) {
                                $like = "%".$q."%";
                                $stmt->bindParam(":id_produto",$like);
                            }
                            $stmt->execute();
                            while ($produto=$stmt->fetchObject()) {
                                ?>
                                <tr class="linha">
                                    <td><?php echo $produto->id_produto; ?></td>
                                    <td><?php echo $produto->nome; ?></td>
                                    <td><?php echo $produto->descricao; ?></td>
                                    <td class="quantidade"><?php echo $produto->qntd_total ?></td>
                                    <td><?php echo number_format($produto->custo_final, 2); ?></td>
                                    <td><?php echo number_format($produto->valor_sugerido,2); ?></td>
                                    <td><?php echo number_format($produto->preco_venda,2); ?></td>
                                    <td>
                                        <a href="produtos-editar.php?idproduto=<?php echo $produto->id_produto; ?>" title="Editar produto"><i class="fa fa-edit fa-lg"></i></a>
                                        <a href="produtos-apagar.php?idproduto=<?php echo $produto->id_produto; ?>" title="Remover produto"><i class="fa fa-times fa-lg"></i></a>
                                    </td>
                                </tr><?php
                    }
                }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/funcoes.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>