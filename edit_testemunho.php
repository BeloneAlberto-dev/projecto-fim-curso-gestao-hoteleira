<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: login.php");
    exit;
}

include 'db.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM testemunho WHERE id=$id");
$testemunho = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nome = $_POST['nome'];
    $feedback = $_POST['feedback'];
    // Se escolher nova imagem
    if(isset($_FILES['imagem']) && $_FILES['imagem']['name'] != ''){

        $imagem = "img/" . $_FILES['imagem']['name'];

        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);

        $sql = "UPDATE testemunho 
                SET nome='$nome',
                    feedback='$feedback',
                    imagem='$imagem'
                WHERE id=$id";

    } else {

        // Mantém a imagem antiga
        $sql = "UPDATE testemunho 
                SET nome='$nome',
                    feedback='$feedback'
                WHERE id=$id";
    }

    if($conn->query($sql)){
        header("Location: dashboard_admin.php");
        exit();

    } else {

        $error = "Erro ao atualizar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Testemunho</title>

<style>
:root {
    --white-color: #fff;
    --dark-color: #252525;
    --primary-color: #3b1c1c;
    --secondary-color: #f3961c;
    --light-pink-color: #faf4f5;
}

/* BODY */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: var(--light-pink-color);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

/* CARD */
.container {
    background: var(--white-color);
    padding: 35px;
    border-radius: 25px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* TITLE */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: var(--primary-color);
}

/* LABEL */
label {
    font-weight: 600;
    color: var(--dark-color);
    display: block;
    margin-top: 12px;
    margin-bottom: 5px;
}

/* INPUTS */
input, select, textarea {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #ddd;
    outline: none;
    transition: 0.3s;
    font-size: 14px;
}

/* TEXTAREA */
textarea {
    resize: none;
    min-height: 100px;
}

/* FOCUS */
input:focus, select:focus, textarea:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 5px rgba(243,150,28,0.4);
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: 12px;
    background: var(--secondary-color);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: var(--primary-color);
    transform: translateY(-2px);
}

/* VOLTAR */
.back-link {
    display: block;
    text-align: center;
    margin-top: 18px;
    text-decoration: none;
    color: var(--secondary-color);
    font-weight: bold;
}

.back-link:hover {
    color: var(--primary-color);
}
</style>
</head>

<body>

<div class="container">
  <h2>Editar Quarto</h2>

  <form method="POST" enctype="multipart/form-data">
      
        <label>Nome</label>
        <input type="text" name="nome" value="<?= $testemunho['nome'] ?>">

        <label>Selecionar imagem</label>
        <input type="file" name="imagem" accept="image/*">
    
        <label>Feedback</label>
        <textarea name="feedback"><?= $testemunho['feedback'] ?></textarea>


    <button type="submit">Atualizar</button>

  </form>
  <a href="javascript:history.back()">← Voltar</a>
</div>

</body>
</html>