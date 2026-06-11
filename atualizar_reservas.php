<?php
include 'db.php';

$sql1 = "UPDATE reservas
         SET status = 'concluida'
         WHERE saida <= CURDATE()
         AND status = 'ativa'";

$sql2 = "UPDATE adm
         SET status = 'concluida'
         WHERE saida <= CURDATE()
         AND status = 'ativa'";

mysqli_query($conn, $sql1);
mysqli_query($conn, $sql2);
?>