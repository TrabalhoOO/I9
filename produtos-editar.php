<?php
require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';


if ($_POST) {
    $idproduto = $_POST['idproduto'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = str_replace(',', '.', ($_POST['preco']));
    $custo = str_replace(',', '.', ($_POST['custo']));


    // Validar informações
    if ($descricao == '') {
        $msg = 'Informe a descrição do produto';
    }

    // Inserir
    if (!isset($msg)) {
        $sql = "Update produto_estoque
        Set nome = :nome,
            descricao = :descricao,
            preco_venda = :preco,
            custo_final = :custo
        Where (id_produto = :idproduto) LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":idproduto", $idproduto);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":custo", $custo);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $msg = 'Erro ao atualizar o registro';
        } else {
            $url = 'produtos-editar.php';
            $_SESSION['retorno'] = TRUE;
            $_SESSION['id_produto'] = $idproduto;
            $_SESSION['produto_alterado'] = $nome;
            javascriptAlertFim($url);
        }
    }
} else {
    
    if (isset($_GET['idproduto'])) {
        $_SESSION['retorno'] = FALSE;
        $_SESSION['id_produto'] = NULL;
        $_SESSION['produto_alterado'] = NULL;
        $idproduto = (int) $_GET['idproduto'];
        $sql = 'Select id_produto, qntd_total, custo_final, valor_sugerido, preco_venda,nome,descricao from produto_estoque WHERE id_produto = :id_produto';
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":id_produto", $idproduto);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $url = 'produtos.php';
                javascriptAlertFim($url);
            }
            $produto = $stmt->fetchObject();
        }
    } elseif (isset($_SESSION['id_produto'])&&$_SESSION['retorno']=TRUE) {

        $idproduto = (int) $_SESSION['id_produto'];
        $sql = 'Select id_produto, qntd_total, custo_final, valor_sugerido, preco_venda,nome,descricao from produto_estoque WHERE id_produto = :id_produto';
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":id_produto", $idproduto);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $url = 'produtos.php';
                javascriptAlertFim($url);
            }
            $produto = $stmt->fetchObject();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Editar produtos</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-pencil"></i> Editar produtos</h1>
            </div>

            <?php
            if (isset($msg)) {
                msgHtml($msg);
            }
            if (isset($_SESSION['retorno']) && $_SESSION['retorno'] == TRUE) {
                $msg = 'Produto ' . $_SESSION['produto_alterado'] . ' alterado com sucesso';
                msgHtml($msg, "success");
            }
            $_SESSION['retorno'] = false;
            ?>

            <form role="form" method="post" action="produtos-editar.php">

                <input type="hidden" name="idproduto" value="<?php echo $idproduto; ?>">
                <div class="form-group">
                    <label for="fnome">Nome</label>
                    <input type="text" class="form-control" id="fnome" name="nome" placeholder="Nome do produto" value="<?php echo $produto->nome; ?>" required>
                </div>
                <div class="form-group">
                    <label for="fdescricao">Descrição</label>
                    <input type="text" class="form-control" id="fdescricao" name="descricao" placeholder="Descrição do produto" value="<?php echo $produto->descricao; ?>" required>
                </div>
                <div class="form-group">
                    <label for="fdescricao">Quantidade Total</label>
                    <input type="text" class="form-control" id="fquant" name="quantidade" placeholder="Quantidade do produto" value="<?php echo $produto->qntd_total; ?>" disabled="true" >
                </div>
                <div class="form-group">
                    <label for="fdescricao">Custo</label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>

                        <input type="text" maxlength="8" class="form-control" id="fcusto" name="custo" placeholder="Custo do produto" value="<?php echo number_format($produto->custo_final, 2); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="fdescricao">Preço Sugerido</label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control" id="fsugerido" name="valor_sugerido" placeholder="Valor Sugerido do produto" value="<?php echo number_format($produto->valor_sugerido, 2); ?>" disabled="true" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="fpreco">Preço</label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control" maxlength="8" id="fpreco" name="preco" placeholder="Preço" value="<?php echo number_format($produto->preco_venda, 2) ?>" required>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Atualizar</button>
                <button type="reset" class="btn btn-danger">Desfazer</button>
                <button type="button" class="btn btn-warning" onclick="window.location='produtos.php'">Sair</button>
            </form>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>