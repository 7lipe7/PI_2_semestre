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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
   <nav>
    <h2>Dashboard</h2>
     <p>Visão geral do pet shop</p>
   </nav>
</body>
</html>