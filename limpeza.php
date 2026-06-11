<?php
include 'db.php';

// Apaga contactos com mais de 7 dias
mysqli_query($conn, "
    DELETE FROM contactos
    WHERE criado_em < DATE_SUB(NOW(), INTERVAL 7 DAY)
");

?>