<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';


$id = $_GET['id'];

// agora apagar quarto
$conn->query("DELETE FROM contactos WHERE id = $id");


header("Location: dashboard_admin.php");
exit();
?>