<?php
session_start();
include 'db.php';

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // md5 suficiente para projeto escolar
    $confirm_password = md5($_POST['confirm_password']);

    if($password != $confirm_password){
        $error = "As senhas não coincidem!";
    } else {
        // Verificar se email já existe
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if($check->num_rows > 0){
            $error = "Email já cadastrado!";
        } else {
            $sql = "INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$password','client')";
            if($conn->query($sql)){
                $_SESSION['success'] = "Conta criada com sucesso! Faça login.";
                  header("Location: login.php");
exit();
            } else {
                $error = "Erro ao registrar tente novamente" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - ENOLEB HOTEL</title>
    <link rel="stylesheet" href="css/login_cadastro.css">
</head>
<body>

<form method="post" class="wrapper">

    <h1>Cadastro</h1>
<?php if(isset($error)) echo "<p style='color:red; text-align:center; background: #afafaf; border-radius:20px;'>$error</p>"; ?>
    <div class="input-box">
        <input type="text" name="name" placeholder="Digite seu nome" required>
    </div>

    <div class="input-box">
        <input type="email" name="email" placeholder="Digite seu E-mail" required>
    </div>

    <div class="input-box">
        <input type="password" name="password" placeholder="Digite sua senha" required>
    </div>

    <div class="input-box">
        <input type="password" name="confirm_password" placeholder="Digite novamente a senha" required>
    </div>

    <button type="submit" class="btn" name="register">Cadastrar</button>

    <div class="register-link">
        <p>Já tem conta? <a href="login.php">Login</a></p>
    </div>

</form>

</body>
</html>