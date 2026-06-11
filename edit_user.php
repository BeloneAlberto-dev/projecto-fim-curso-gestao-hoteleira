<?php
session_start(); 

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){     
    header("Location: login.php");     
    exit; 
}  

include 'db.php';

$id = $_GET['id_client'];

$result = $conn->query("SELECT * FROM users WHERE id_client=$id");
$user = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if(!empty($password)){
        $passwornova = md5($password);

        $sql = "UPDATE users 
                SET name='$name', email='$email', role='$role', password='$passwornova' 
                WHERE id_client=$id";
    } else {
        $sql = "UPDATE users 
                SET name='$name', email='$email', role='$role' 
                WHERE id_client=$id";
    }

    if($conn->query($sql)){
        header("Location: dashboard_admin.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Editar Usuário</title>

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
    max-width: 450px;
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

/* INPUT */
input, select {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #ddd;
    outline: none;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 5px rgba(243,150,28,0.4);
}

/* HINT */
.password-hint {
    font-size: 12px;
    color: #777;
    margin-top: 5px;
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
  <h2>Editar Usuário</h2>

  <form method="POST">
    
    <label>Nome</label>
    <input type="text" name="name" value="<?= $user['name'] ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= $user['email'] ?>" required>

    <label>Tipo de usuário</label>
    <select name="role">
      <option value="client" <?= $user['role'] == 'client' ? 'selected' : '' ?>>Cliente</option>
      <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
      <option value="funcionario" <?= $user['role'] == 'funcionario' ? 'selected' : '' ?>>Funcionario</option>
    </select>

    <label>Nova senha (opcional)</label>
    <input type="password" name="password" placeholder="Deixe vazio para não alterar">
    <div class="password-hint">A senha só será alterada se preencher</div>

    <button type="submit">Atualizar</button>

  </form>

   <a href="javascript:history.back()" class="back-link">← Voltar</a>
</div>

</body>
</html>