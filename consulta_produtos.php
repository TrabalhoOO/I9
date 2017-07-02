<?php

require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

$parametro = (isset($_POST['parametro'])) ? $_POST['parametro'] : '';


// Verifica se foi solicitado uma consulta para o autocomplete



$sql = "SELECT preco_venda from produto_estoque";
if ($parametro != '') {
    $sql .= " WHERE id_produto = :id";
}

if ($stmt = $conn->prepare($sql)) {

    if ($parametro != null) {
    
        $stmt->bindParam(':id', $parametro);
    }
    
    $stmt->execute();
    $dados = $stmt->fetchObject();

    $json = json_encode($dados);
    echo $json;
}

    