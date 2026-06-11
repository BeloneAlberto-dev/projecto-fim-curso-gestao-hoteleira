<?php
session_start(); 
if(!isset($_SESSION['user_id'])){     
    header("Location: login.php");     
    exit(); 
}  

include 'db.php';

$id = $_GET['id_reserva'];

$result = $conn->query("SELECT * FROM reservas WHERE id_reserva=$id");
$reserva = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){
$tipo = !empty($_POST['tipo']) ? $_POST['tipo'] : $reserva['tipo'];
$entrada = !empty($_POST['entrada']) ? $_POST['entrada'] : $reserva['entrada'];
$saida = !empty($_POST['saida']) ? $_POST['saida'] : $reserva['saida'];

$sqlCheck1 = "SELECT * FROM adm 
              WHERE tipo = '$tipo'
              AND id_reserva != $id
              AND (
                ('$entrada' BETWEEN entrada AND saida)
                OR ('$saida' BETWEEN entrada AND saida)
                OR (entrada BETWEEN '$entrada' AND '$saida')
              )";

$sqlCheck2 = "SELECT * FROM reservas 
              WHERE tipo = '$tipo'
              AND id_reserva != $id
              AND (
                ('$entrada' BETWEEN entrada AND saida)
                OR ('$saida' BETWEEN entrada AND saida)
                OR (entrada BETWEEN '$entrada' AND '$saida')
              )";

$check1 = $conn->query($sqlCheck1);
$check2 = $conn->query($sqlCheck2);

if (($check1 && $check1->num_rows > 0) || ($check2 && $check2->num_rows > 0)) {
    $error = "Data já ocupada para este quarto";
} else {

 $sql = "UPDATE reservas 
            SET tipo='$tipo', entrada='$entrada', saida='$saida', data_reserva=NOW()
            WHERE id_reserva=$id  AND status = 'pendente'";

        if ($conn->query($sql) === TRUE) {
           header("Location: usuario_logado.php");
           exit();
        } else {
            $error = "Erro ao registrar reserva: " . $conn->error;
        }
    }
}

//buscar tipos de quartos
$rooms_sql = "SELECT * FROM rooms "; 
$rooms_result = $conn->query($rooms_sql); 
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Atualizar Reserva</title>

<style>
:root {
    --white-color: #fff;
    --dark-color: #252525;
    --primary-color: #3b1c1c;
    --secondary-color: #f3961c;
    --light-pink-color: #faf4f5;
    --medium-gray-color: #ccc;

    --border-radius-s: 8px;
    --border-radius-m: 20px;
}

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* FUNDO */
body {
    background: var(--light-pink-color);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CARD */
.container {
    background: var(--white-color);
    padding: 35px;
    border-radius: var(--border-radius-m);
    width: 100%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-top: 6px solid var(--secondary-color);
}

/* TITULO */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: var(--primary-color);
}

/* LABEL */
label {
    font-weight: bold;
    color: var(--dark-color);
    display: block;
    margin-top: 12px;
    margin-bottom: 5px;
}

/* INPUTS */
.form-input {
    width: 100%;
    padding: 10px;
    border-radius: var(--border-radius-s);
    border: 1px solid var(--medium-gray-color);
    outline: none;
    transition: 0.3s;
}

.form-input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* BOTÃO */
.submit-button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: var(--border-radius-s);
    background: var(--secondary-color);
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.submit-button:hover {
    background: var(--primary-color);
}

/* ERRO */
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
    background: #ffe5e5;
    padding: 8px;
    border-radius: var(--border-radius-s);
}

/* LINK VOLTAR */
.back-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: var(--primary-color);
    font-weight: bold;
}

.back-link:hover {
    color: var(--secondary-color);
}
</style>
</head>

<body>

<div class="container">
  <h2>Atualizar Reserva</h2>

  <form method="post">

    <?php 
    if(isset($error)) echo "<p class='error'>$error</p>";
    ?>                  

    <label>Tipo de Quarto</label>
    <select name="tipo" class="form-input">
        <?php while($room = $rooms_result->fetch_assoc()): ?>
            <option value="<?= $room['tipo'] ?>" 
                <?= ($room['tipo'] == $reserva['tipo']) ? 'selected' : '' ?>>
                <?= $room['tipo'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Data de Entrada</label>
    <input type="date" name="entrada" 
        value="<?= $reserva['entrada'] ?>" 
        min="<?= date('Y-m-d') ?>" 
        class="form-input">

    <label>Data de Saída</label>
    <input type="date" name="saida" 
        value="<?= $reserva['saida'] ?>" 
        min="<?= date('Y-m-d') ?>" 
        class="form-input">

    <button type="submit" class="submit-button">Atualizar</button>

  </form>

    <a href="javascript:history.back()" class="back-link">← Voltar</a>

</div>

</body>
</html>