<?php

require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';

// Pegar idvenda
if (!isset($_SESSION['idvenda'])) {
    header('location:vendas.php');
    exit;
}
$idvenda = $_SESSION['idvenda'];

// Validar idvenda
$sql = "Select id_pedido
From pedido
Where
    (id_pedido = :pedido)
    And (fechada = " . VENDA_ABERTA . ")";
if ($stmt = $conn->prepare($sql)) {

    $stmt->bindParam(":pedido", $idvenda);

    $stmt->execute();
    $venda = $stmt->fetchObject();
}

if (!$venda) {
    header('location:vendas.php');
    exit;
}

// Fechar venda

$sql = "Update pedido Set fechada=" . VENDA_FECHADA
        . " Where (id_pedido = :pedido )";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bindParam(":pedido", $idvenda);

    $stmt->execute();
    
}
// Redirecionar usuario para vendas.php
unset($_SESSION['idvenda']);
header('location:venda-detalhes.php?idvenda=' . $idvenda);
