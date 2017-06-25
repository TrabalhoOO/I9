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
        <title>Clientes</title>

        <?php headCss(); ?>
    </head>
    <body>

        <?php
        criarNav($_SESSION['tipo']);
        ?>

        <div class="container">

            <div class="page-header">
                <h1><i class="fa fa-user fa-lg"></i> Relatório de Clientes</h1>
            </div>

            <form role="form" method="post" action="clientes.php">
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-6">
                        <label for="festado">Estado</label>
                        <select id="festado" name="estado" class="form-control">
                            <option value="Todos">Todos os Estados</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 col-xs-6">
                        <label for="fordenar">Ordenar Por: </label>
                        <select id="fordenar" name="ordernar" class="form-control">
                            <option value="cliente" >Nome do Cliente</option>
                            <option value="estado">UF do Cliente</option>
                        </select>




                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><span class="fa fa-file fa-lg"></span> Gerar Relatório</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>

            </form>

        </div>

        <script src="./lib/jquery.js"></script>
        <script src="./lib/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>