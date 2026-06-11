<?php

header("Content-Type: application/json");
include 'db.php';

if ($conn->connect_error) {
    die(json_encode(["erro" => "Erro na conexão"]));
}

$tipo = $_GET['tipo'] ?? '';

$sql = "SELECT id_reserva, tipo, entrada, saida FROM reservas WHERE tipo = ?
        UNION
        SELECT id_reserva, tipo, entrada, saida FROM adm WHERE tipo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $tipo, $tipo);

$stmt->execute();

$result = $stmt->get_result();

$reservas = [];

while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

echo json_encode($reservas);
exit;