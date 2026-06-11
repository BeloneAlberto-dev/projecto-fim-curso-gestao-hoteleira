<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$id = $_GET['id_client'];

$conn->query("DELETE FROM users WHERE id_client=$id");

header("Location: dashboard_admin.php");
?>