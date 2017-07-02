<?php

require './protege.php';
require './config.php';
require './lib/funcoes.php';
require './lib/conexao.php';


//pegar id usuario
$idcliente = $_SESSION['id_pessoa'];

// Verificar se existe uma venda aberta para $idcliente
// Se existir não abrir outra venda
$sql = "Select id_pedido
From pedido
Where
  (id_cli = :idpessoa)
  And (fechada = " . VENDA_ABERTA . ")";
if ($stmt = $conn->prepare($sql)) {

    $stmt->bindParam(":idpessoa", $idcliente);

    $stmt->execute();
    $venda = $stmt->fetchObject();
    if ($venda) {
        // Existe outra venda
        $idvenda = $_SESSION['idvenda'] = $venda->id_pedido;
        header('location:venda-produto.php?idvenda='.$idvenda);
        exit;
    }
}

// Criar uma venda
//Criar um registro na tabela venda
    $data = date('Y-m-d');

    $sql = "Insert into pedido
(data,id_cli,valorTotal,fechada)
Values
(:data, :id_cliente, 0, 0)";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bindParam(":data", $data);
        $stmt->bindParam(":id_cliente", $idcliente);

        $stmt->execute();
        $idvenda = $conn->lastInsertId();
        $_SESSION['idvenda'] = $idvenda;

        header('location:venda-produto.php?idvenda=' . $idvenda);
    }
