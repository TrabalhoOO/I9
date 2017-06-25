<?php

try {
    $conn = new PDO("mysql:host=".BD_HOST.";port=".BD_PORTA.";dbname=".BD_NOME."",BD_USUARIO,BD_SENHA);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES'utf8'");
    $conn->exec('SET character_set_connection=utf8');
    $conn->exec('SET character_set_client=utf8');
    $conn->exec('SET character_set_results=utf8');
    //echo 'Conectado com Sucesso!';
}catch(PDOException $e){ 
    //var_dump($e);
    echo $e->getMessage();
}
