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
                WHERE id_reserva = ?
                AND id_client = ?
                AND status ='pendente'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        $stmt->execute();

         if ($stmt->affected_rows > 0) {
        header("Location: usuario_logado.php?msg=apagada#reservas");
    } else {
        header("Location: usuario_logado.php?erro=estado_invalido#reservas");
    }
    exit();
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