<?php
require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';

$msgOk = array();
$msgAviso = array();

if (!isset($_GET['idvenda'])) {
    header('location:vendas.php');
    exit;
}
$tipo = $_SESSION['tipo'];
$idpessoa = $_SESSION['id_pessoa'];
$idvenda = (int) $_GET['idvenda'];
if ($tipo == 'gerente') {
    $sql = "Select
	v.id_pedido,
	v.data,
        v.valorTotal,
	p.nome clienteNome,
        c.rua,cidade.cidade,
        cidade.estado From pedido v Inner Join cliente c On (c.FK_pessoa = v.id_cli)
 Inner Join pessoa p on(c.FK_pessoa = p.id_pessoa)
Inner Join cidade on(c.FK_cidade = cidade.id_cidade)
Where
    (v.id_pedido = $idvenda)
    And (v.fechada = " . VENDA_FECHADA . ")";
} elseif ($tipo == 'cliente') {
    $sql = "Select
	v.id_pedido,
	v.data,
        v.valorTotal,
	p.nome clienteNome,
        c.rua,cidade.cidade,
        cidade.estado From pedido v Inner Join cliente c On (c.FK_pessoa = v.id_cli)
 Inner Join pessoa p on(c.FK_pessoa = p.id_pessoa)
Inner Join cidade on(c.FK_cidade = cidade.id_cidade)
Where
    (v.id_pedido = $idvenda)
    And (v.fechada = " . VENDA_FECHADA . ") and "
            . "(v.id_cli = :cliente)";
}
if ($stmt = $conn->prepare($sql)) {
    if ($tipo == 'cliente') {
        $stmt->bindParam(":cliente", $idpessoa);
    }
    $stmt->execute();
}
$venda = $stmt->fetchObject();
if (!$venda) {
    header('location:vendas.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Detalhes da venda</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-shopping-cart"></i> Detalhes da venda #<?php echo $idvenda; ?></h1>
            </div>

            <?php
            if ($msgOk) {
                msgHtml($msgOk, 'success');
            }
            ?>
            <?php
            if ($msgAviso) {
                msgHtml($msgAviso, 'warning');
            }
            ?>
             <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Detalhes do Cliente</h3>

                </div>
                <div class="panel-body">
                    <div class="row col-lg-8">
                        <h3 class="panel-title">Cliente: <?php echo $venda->clienteNome; ?></h3>
                        <h3 class="panel-title">Rua: <?php echo $venda->rua; ?></h3>
                    </div>
                    <div class="row col-lg-4">
                        <h3 class="panel-title">Cidade: <?php echo $venda->cidade; ?></h3>
                        <h3 class="panel-title">Estado: <?php echo Estados($venda->estado); ?></h3>
                    </div>
                </div>
             </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Produtos da venda</h3>

                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Qtd.</th>
                            <th>Produto</th>
                            <th>Preço unitário</th>
                            <th>Preço total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $itens = "Select
	v.FK_estoqGlobal,
	p.nome,
	v.valorUnitario,
	v.quantidade
From itempedido v
Inner Join produto_estoque p
	On (p.id_produto = v.FK_estoqGlobal)
Where (v.FK_pedido = $idvenda)";
                        if ($stmt = $conn->prepare($itens)) {
                            $stmt->execute();

                            $vendaTotal = $venda->valorTotal;

                            while ($produto = $stmt->fetchObject()) {
                                $total = $produto->quantidade * $produto->valorUnitario;
                                ?>
                                <tr>
                                    <td><?php echo $produto->quantidade; ?></td>
                                    <td><?php echo $produto->nome; ?></td>
                                    <td>R$ <?php echo number_format($produto->valorUnitario, 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th colspan="2">Total da venda</th>
                            <th>R$ <?php echo number_format($vendaTotal, 2, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" class="btn btn-success hidden-print" onclick="window.print()"><span class="fa fa-print fa-lg"></span> Imprimir</button>

            </div>



            <script src="./lib/jquery.js"></script>
            <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>