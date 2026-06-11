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

// pegar dados da URL
$id = $_GET['id_reserva'];
$tabela = $_GET['tabela'] ?? 'reservas';

// buscar dados
$result = $conn->query("SELECT * FROM $tabela WHERE id_reserva=$id");
$reserva = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $tipo = !empty($_POST['tipo']) ? $_POST['tipo'] : $reserva['tipo'];
    $entrada = !empty($_POST['entrada']) ? $_POST['entrada'] : $reserva['entrada'];
    $saida = !empty($_POST['saida']) ? $_POST['saida'] : $reserva['saida'];
    $status = !empty($_POST['status']) ? $_POST['status'] : ($reserva['status'] ?? 'pendente');

    $sqlCheck = "SELECT tipo, entrada, saida FROM reservas 
             WHERE tipo = '$tipo'
             AND id_reserva != $id
             AND status IN ('ativa', 'bloqueada')
             AND NOT ('$saida' <= entrada OR '$entrada' >= saida)

             UNION

             SELECT tipo, entrada, saida FROM adm 
             WHERE tipo = '$tipo'
             AND id_reserva != $id
             AND NOT ('$saida' <= entrada OR '$entrada' >= saida)";

    $check = $conn->query($sqlCheck);

    if ($check && $check->num_rows > 0) {
        $error = "Este quarto já está reservado nesse período.";
    } else {

        if ($tabela == 'reservas') {
            $sql = "UPDATE reservas 
                    SET tipo='$tipo', entrada='$entrada', saida='$saida', status='$status', data_reserva=NOW()
                    WHERE id_reserva=$id AND status != 'concluida'";
        } else {
            $sql = "UPDATE adm 
                    SET tipo='$tipo', entrada='$entrada', saida='$saida', status='$status', data_reserva=NOW()
                    WHERE id_reserva=$id AND status != 'concluida'";
        }

        if ($conn->query($sql) === TRUE) {
           //  REDIRECIONAR CERTO
            if ($_SESSION['user_role'] == 'admin') {
                  header("Location: dashboard_admin.php");
            } else {
                     header("Location: dashboard_funcionario.php");
                    }
            
        } else {
            $error = "Erro: " . $conn->error;
        }
    }
}

// buscar quartos
$rooms_result = $conn->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Reserva</title>

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
select, input {
    width: 100%;
    padding: 10px;
    border-radius: var(--border-radius-s);
    border: 1px solid var(--medium-gray-color);
    outline: none;
    transition: 0.3s;
}

select:focus, input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* BOTÃO */
button {
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

button:hover {
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

/* LINK */
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
  <h2>Editar Reserva</h2>

  <form method="post">

    <?php 
    if(isset($error)) echo "<p class='error'>$error</p>";
    ?>

    <label>Tipo</label>
    <select name="tipo">
    <?php while($room = $rooms_result->fetch_assoc()): ?>
        <option value="<?= $room['tipo'] ?>" 
            <?= ($room['tipo'] == $reserva['tipo']) ? 'selected' : '' ?>>
            <?= $room['tipo'] ?>
        </option>
    <?php endwhile; ?>
    </select>

    <label>Entrada</label>
    <input type="date" name="entrada" value="<?= $reserva['entrada'] ?>">

    <label>Saída</label>
    <input type="date" name="saida" value="<?= $reserva['saida'] ?>">

    <label>Status</label>
    <select name="status">
        <option value="pendente" <?= ($reserva['status'] ?? '') == 'pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="bloqueada" <?= ($reserva['status'] ?? '') == 'bloqueada' ? 'selected' : '' ?>>Bloqueada</option>
        <option value="ativa" <?= ($reserva['status'] ?? '') == 'ativa' ? 'selected' : '' ?>>Ativa</option>
    </select>

    <button type="submit">Atualizar</button>

  </form>
  <a href="javascript:history.back()" class="back-link">← Voltar</a>
</div>

</body>
</html>