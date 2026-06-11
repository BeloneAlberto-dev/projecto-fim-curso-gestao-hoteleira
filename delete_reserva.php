<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$id = $_GET['id_reserva'] ?? null;
$tabela = $_GET['tabela'] ?? '';

if ($id && ($tabela == 'reservas' || $tabela == 'adm')) {

    // 🔐 SE FOR CLIENTE → só pode apagar a própria reserva
    if ($_SESSION['user_role'] == 'client') {
        $sql = "DELETE FROM reservas 
                WHERE id_reserva = ? AND id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        $stmt->execute();

    } 
    // 🔐 SE FOR ADMIN → pode apagar qualquer uma
    elseif ($_SESSION['user_role'] == 'admin') {
        $sql = "DELETE FROM $tabela WHERE id_reserva = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// 🔁 REDIRECIONAR CERTO
if ($_SESSION['user_role'] == 'admin') {
    header("Location: dashboard_admin.php");
} else {
    header("Location: usuario_logado.php");
}
exit;
?>