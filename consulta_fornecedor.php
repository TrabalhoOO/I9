<?php

require_once './protege.php';
require_once './config.php';
require_once './lib/funcoes.php';
require_once './lib/conexao.php';

$parametro = (isset($_GET['parametro'])) ? $_GET['parametro'] : '';


// Verifica se foi solicitado uma consulta para o autocomplete



$sql = "SELECT f.fk_pessoa, p.nome FROM fornecedor as f inner join pessoa as p on(f.fk_pessoa = p.id_pessoa)";
if ($parametro != '') {
    $sql .= "WHERE nome LIKE :nome_fornecedor";
}

if ($stmt = $conn->prepare($sql)) {

    if ($parametro != null) {
        $like = "%" . $parametro . "%";
        $stmt->bindParam(':nome_fornecedor', $like);
    }
    
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_OBJ);

    $json = json_encode($dados);
    echo $json;
}

    