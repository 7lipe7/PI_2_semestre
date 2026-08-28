<?php

require_once __DIR__ . '/../src/config/database.php';


try {
    $pdo = getConnection();
    $versao = $pdo->query('SELECT VERSION()')->fetchColumn();

    echo "<p style='color:green'>&#9989; MySQL conectado com sucesso! Versão: <strong>{$versao}</strong></p>";
} catch (Exception $e) {
    echo "<p style='color:red'>&#10060; Falha na conexão com o MySQL: " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <nav>
    <h2>Dashboard</h2>
     <p>Visão geral do pet shop</p>
   </nav>
</body>
</html>