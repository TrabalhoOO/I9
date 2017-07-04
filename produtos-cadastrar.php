<?php
require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';


if ($_POST) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $preco = str_replace(',', '.', ($_POST['preco']));
    $custo = str_replace(',', '.', ($_POST['custo']));


    // Validar informações
    if ($descricao == '') {
        $msg = 'Informe a descrição do produto';
    }

    // Inserir
    if (!isset($msg)) {
        $sql = "Insert into produto_estoque
        (nome,descricao,qntd_total,preco_venda,custo_final)
        VALUES(:nome,:descricao,:quantidade,:preco_venda,:custo_final)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":preco_venda", $preco);
        $stmt->bindParam(":custo_final", $custo);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $msg = 'Erro ao atualizar o registro';
        } else {
            $url = 'produtos.php';
            $_SESSION['retorno_cadastro'] = TRUE;
            $_SESSION['produto_cadastrado'] = $nome;
            javascriptAlertFim($url);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cadastrar produto</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-pencil"></i> Cadastrar Produto</h1>
            </div>



            <form role="form" method="post" action="produtos-cadastrar.php">
                <div class="form-group">
                    <label for="fnome">Nome</label>
                    <input type="text" class="form-control" id="fnome" name="nome" placeholder="Nome do produto" required>
                </div>
                <div class="form-group">
                    <label for="fdescricao">Descrição</label>
                    <input type="text" class="form-control" id="fdescricao" name="descricao" placeholder="Descrição do produto"  required>
                </div>
                <div class="form-group">
                    <label for="fdescricao">Quantidade Total</label>
                    <input type="text" class="form-control" id="fquant" name="quantidade" placeholder="Quantidade do produto" >
                </div>
                <div class="form-group">
                    <label for="fdescricao">Custo</label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>

                        <input type="text" maxlength="8" class="form-control" id="fcusto" name="custo" placeholder="Custo do produto"  >
                    </div>
                </div>
                <div class="form-group">
                    <label for="fpreco">Preço</label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control" maxlength="8" id="fpreco" name="preco" placeholder="Preço" required>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-danger">Desfazer</button>
                <button type="button" class="btn btn-warning" onclick="window.location = 'produtos.php'">Sair</button>
            </form>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script src="./lib/autoComplete_fornecedor.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>