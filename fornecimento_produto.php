<?php
require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';

$msgOk = array();
$msgAviso = array();

/*
  Valores para acao
  1 = Incluir produto na venda
  2 = Remover produto na venda
 */
$acao = 0;
if (isset($_GET['acao'])) {
    $acao = (int) $_GET['acao'];
} elseif (isset($_POST['acao'])) {
    $acao = (int) $_POST['acao'];
}

if ($acao == 1) {
    $idproduto = (int) $_POST['idproduto'];
    $data = date('Y-m-d');
    $qtd = $_POST['qtd'];
    $fornecedor = $_POST['fornecedor'];
    $custo = $_POST['custoUnitario'];
    $sql = "INSERT INTO fornecimento
            VALUES (:fornecedor, :produto, :dataEntrada, :qtd,:custoUnitario)";
    if ($stmt = $conn->prepare($sql)) {

        $stmt->bindParam(":custoUnitario", $custo);
        $stmt->bindParam(":dataEntrada", $data);
        $stmt->bindParam(":produto", $idproduto);
        $stmt->bindParam(":qtd", $qtd);
        $stmt->bindParam(":fornecedor", $fornecedor);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            $msgOk[] = "Adicionado $qtd x " . $produto->nome;
        } else {
            $msgAviso[] = "Erro para inserir o produto na venda: ";
        }
    }
}

if ($acao == 2) {
    $idproduto = (int) $_GET['idproduto'];

    $sql = "Delete From itempedido Where (FK_estoqGlobal = :produto) LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {

        $stmt->bindParam(":produto", $idproduto);

        $stmt->execute();
    }

    $msgOk[] = "Produto removido da venda";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Produtos da venda</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-shopping-cart"></i>Fornecimento</h1>
            </div>

            <?php
            if ($msgOk) {
                msgHtmlArray($msgOk, 'success');
            }
            ?>
            <?php
            if ($msgAviso) {
                msgHtmlArray($msgAviso, 'warning');
            }
            ?>

            <form role="form" method="post" action="fornecimento_produto.php">

                <input type="hidden" name="acao" value="1">

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Adicionar produto</h3>
                    </div>

                    <div class="panel-body">

                        <div class="container-fluid">
                            <div class="row">

                                <div class="col-xs-12 col-sm-6 col-md-8">
                                    <div class="form-group">
                                        <label for="fidproduto">Produto</label>
                                        <select id="fidproduto" name="idproduto" class="form-control" required>
                                            <option value="">Selecione um produto</option>
                                            <?php
                                            $sql = 'Select * From produto_estoque';
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($linha = $result->fetchObject()) {
                                                ?>
                                                <option value="<?php echo $linha->id_produto; ?>"><?php echo $linha->nome; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-xs-4 ">
                                        <label for="nomefornecedor">Fornecedor</label>
                                        <input type="hidden" class="form-control" id="fornecedor" name="fornecedor" placeholder="ID" value="3" >
                                        <input type="text" class="form-control" id="nomefornecedor" name="nome_fornecedor" placeholder="Fornecedor do produto" >
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label for="fqtd">Quantidade</label>
                                        <input type="number" class="form-control" id="fqtd" value="0" name="qtd" min="1" required>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label for="fpreco">Custo Unitário</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text" class="form-control" id="fpreco" name="custoUnitario" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Inserir</button>
                        <button type="reset" class="btn btn-danger">Limpar</button>
                    </div>
                </div>
            </form>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Produtos da venda</h3>
                </div>

                <table id='produtos' class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Qtd.</th>
                            <th>Produto</th>
                            <th>Preço unitário</th>
                            <th>Preço total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "Select
	v.FK_estoqGlobal,
	p.nome,
	v.valorUnitario,
	v.quantidade
From itempedido v
Inner Join produto_estoque p
	On (p.id_produto = v.FK_estoqGlobal)
        inner join pedido on (pedido.id_pedido = v.FK_pedido)
Where (v.FK_pedido = :venda)";
                        if ($result = $conn->prepare($sql)) {
                            $result->bindParam(':venda', $idvenda);
                            $result->execute();
                        }

                        $vendaTotal = 0;
                        while ($produto = $result->fetchObject()) {
                            $total = $produto->quantidade * $produto->valorUnitario;
                            $vendaTotal += $total;
                            ?>
                            <tr>
                                <td><?php echo $produto->quantidade; ?></td>
                                <td><?php echo $produto->nome; ?></td>
                                <td>R$ <?php echo number_format($produto->valorUnitario, 2, ',', '.'); ?></td>
                                <td>R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                <td><a href="venda-produto.php?acao=2&idproduto=<?php echo $produto->FK_estoqGlobal; ?>" title="Remover produto da venda"><i class="fa fa-times fa-lg"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th colspan="2">Total da venda</th>
                            <th>R$ <?php echo number_format($vendaTotal, 2, ',', '.'); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <form class="form-horizontal" method="post" action="venda-fechar.php">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Fechamento da venda</h3>
                    </div>

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="fcliente" class="col-sm-2 control-label">Código:</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?php echo $idvenda; ?></p>
                            </div>

                            <label for="fcliente" class="col-sm-2 control-label">Data:</label>
                            <div class="col-sm-2">
                                <p class="form-control-static"><?php echo date('d/m/Y', strtotime($venda->data)); ?></p>
                            </div>

                            <label for="fcliente" class="col-sm-2 control-label">Total:</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">R$ <?php echo number_format($vendaTotal, 2, ',', '.'); ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fcliente" class="col-sm-2 control-label">Cliente:</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><?php echo $venda->clienteNome; ?></p>
                            </div>


                        </div>



                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-success" id="fechar" disabled="true">Fechar venda</button>
                    </div>
                </div>
            </form>

        </div>
        <script></script>
        <script src="./lib/jquery.js"></script>
        <script src="./lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script src="./lib/autoComplete_fornecedor.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>
        <script>
            if ($('#produtos tbody tr').length > 0) {
                $('#fechar').prop("disabled", false);
            }
        </script>

    </body>
</html>