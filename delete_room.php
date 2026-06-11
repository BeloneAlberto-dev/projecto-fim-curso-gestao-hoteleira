<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';


$id = $_GET['id_rooms'];

// agora apagar quarto
$conn->query("DELETE FROM rooms WHERE id_rooms = $id");

// 🔁 REDIRECIONAR CERTO
if ($_SESSION['user_role'] == 'admin') {
    header("Location: dashboard_admin.php");
} else {
    header("Location: dashboard_funcionario.php");
}
?>