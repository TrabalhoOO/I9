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
$sql = "Select idvenda
From venda
Where
    (idvenda = $idvenda)
    And (status = " . VENDA_ABERTA . ")";
$consulta = mysqli_query($con, $sql);
$venda = mysqli_fetch_assoc($consulta);

if (!$venda) {
  header('location:vendas.php');
  exit;
}

// Fechar venda
$sql = "Update venda Set status=" . VENDA_FECHADA
        . " Where (idvenda = $idvenda)";
mysqli_query($con, $sql);
unset($_SESSION['idvenda']);

// Redirecionar usuario para vendas.php
header('location:venda-detalhes.php?idvenda=' . $idvenda);
