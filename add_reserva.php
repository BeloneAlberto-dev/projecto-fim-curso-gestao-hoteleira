<?php 
session_start(); 

if(
    !isset($_SESSION['user_id']) || 
    ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'funcionario')
){     
    header("Location: login.php");     
    exit; 
}  
include 'db.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    $entrada = $_POST['entrada'];
    $saida = $_POST['saida'];

    // Verifica se a data de saída é maior que a de entrada
    if (strtotime($saida) <= strtotime($entrada)) {
        $error = "A data de saída deve ser maior que a data de entrada.";
    } else {

        $sqlCheck1 = "SELECT * FROM adm 
                      WHERE tipo = '$tipo'
                      AND (
                        ('$entrada' BETWEEN entrada AND saida)
                        OR ('$saida' BETWEEN entrada AND saida)
                        OR (entrada BETWEEN '$entrada' AND '$saida')
                      )";

        $sqlCheck2 = "SELECT * FROM reservas 
                      WHERE tipo = '$tipo'
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

            $id_client = $_SESSION['user_id'];

            $sql = "INSERT INTO adm 
                    (id_client, name, email, tipo, entrada, saida, data_reserva)
                    VALUES 
                    ('$id_client','$name', '$email', '$tipo', '$entrada', '$saida', NOW())";

            if ($conn->query($sql) === TRUE) {

            if ($_SESSION['user_role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_funcionario.php");
            }
            exit();

        } else {
            $error = "Erro: " . $conn->error;
        }
        }
    }
}
$rooms_sql = "SELECT * FROM rooms"; 
$rooms_result = $conn->query($rooms_sql); 
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Fazer Reserva</title>

<style>
:root {
    --white-color: #fff;
    --dark-color: #252525;
    --primary-color: #3b1c1c;
    --secondary-color: #f3961c;
    --light-pink-color: #faf4f5;
    --medium-gray-color: #ccc;

    --border-radius-m: 20px;
}

/* ===== BODY ===== */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: var(--light-pink-color);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* ===== CARD ===== */
.container {
    background: var(--white-color);
    padding: 35px;
    border-radius: var(--border-radius-m);
    width: 100%;
    max-width: 520px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    position: relative;
}

/* topo laranja */
.container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: var(--secondary-color);
    border-top-left-radius: var(--border-radius-m);
    border-top-right-radius: var(--border-radius-m);
}

/* ===== TITLE ===== */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: var(--primary-color);
}

/* ===== LABEL ===== */
label {
    font-weight: bold;
    color: var(--primary-color);
    margin-top: 12px;
    display: block;
}

/* ===== INPUTS ===== */
.form-input {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid var(--medium-gray-color);
    outline: none;
    transition: 0.3s;
}

.form-input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* ===== BUTTON ===== */
.submit-button {
    width: 100%;
    padding: 12px;
    margin-top: 22px;
    border: none;
    border-radius: 8px;
    background: var(--secondary-color);
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.submit-button:hover {
    background: var(--primary-color);
}

/* ===== ALERTAS ===== */
.error {
    color: #721c24;
    background: #f8d7da;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 12px;
}

.success {
    color: #155724;
    background: #d4edda;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 12px;
}
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
  <h2>Fazer Reserva</h2>

  <form method="post">

    <?php 
    if(isset($error)) echo "<p class='error'>$error</p>";

    if(isset($_SESSION['success'])) {
        echo "<p class='success'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>              

    <label>Nome</label>
    <input type="text" name="name" class="form-input" required>      

    <label>Email</label>
    <input type="email" name="email" class="form-input" required>      

    <label>Tipo de Quarto</label>
    <select name="tipo" class="form-input" required>
        <?php while($room = $rooms_result->fetch_assoc()): ?>
            <option value="<?= $room['tipo'] ?>">
                <?= $room['tipo'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Data de Entrada</label>
    <input type="date" name="entrada" min="<?= date('Y-m-d') ?>" class="form-input" required>

    <label>Data de Saída</label>
    <input type="date" name="saida" min="<?= date('Y-m-d') ?>" class="form-input" required>

    <button type="submit" class="submit-button">RESERVAR</button>

  </form>
  <a href="javascript:history.back()" class="back-link">← Voltar</a>
</div>

</body>
</html>