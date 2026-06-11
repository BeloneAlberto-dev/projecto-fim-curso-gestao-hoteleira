<?php
session_start(); 
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){     
    header("Location: login.php");     
    exit; 
}  

include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']); 
    $role = $_POST['role'];

    if($password != $confirm_password){
        $error = "As senhas não coincidem!";
    } else {
        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', '$role')";

        if($conn->query($sql)){
             $_SESSION['success'] = "Quarto adicionado com sucesso!";
            header("Location: add_user.php");
            exit();
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Criar Usuário</title>

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
    max-width: 450px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-top: 6px solid var(--secondary-color);
}

/* TITULO */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: var(--primary-color);
}

/* INPUTS */
input, select {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border-radius: var(--border-radius-s);
    border: 1px solid var(--medium-gray-color);
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* BOTÃO */
button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
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
    background: #ffe5e5;
    padding: 10px;
    border-radius: var(--border-radius-s);
    margin-bottom: 10px;
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
  <h2>Criar Usuário</h2>

  <form method="POST">
        <?php
  if(isset($_SESSION['success'])) {
    echo "<p style='color:green;  text-align:center;'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
    }?> 
    <?php 
    if(isset($error)) echo "<p class='error'>$error</p>";
    ?>

    <input type="text" name="name" placeholder="Nome" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    <input type="password" name="confirm_password" placeholder="Confirmar senha" required>

    <select name="role">
        <option value="client">Usuário</option>
        <option value="admin">Admin</option>
        <option value="funcionario">Funcionario</option>
    </select>

    <button type="submit">Salvar</button>
  </form>

    <a href="dashboard_admin.php" class="back-link">← Voltar</a>
</div>

</body>
</html>