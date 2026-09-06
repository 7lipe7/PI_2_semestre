<?php

require_once __DIR__ . '/../src/config/database.php';

try {
    $pdo = getConnection();

} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados.");
}


// ======= Header =======
require_once __DIR__ . '/../src/Views/layout/header.php';


// ======= Sidebar =======
require_once __DIR__ . '/../src/Views/layout/sidebar.php';

?>

<main class="container-fluid">

</main>

<?php

// ======= Footer =======
require_once __DIR__ .'/../src/Views/layout/footer.php';

?>
