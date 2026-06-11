<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id_client'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if($user['role'] == 'admin'){
            header("Location: dashboard_admin.php");
            exit();
        }  if($user['role'] == 'funcionario'){
            header("Location: dashboard_funcionario.php");
            exit();
        } else {
            header("Location: usuario_logado.php");
            exit();
        }
    } else {
        $error = "Email ou senha inválida!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Login - ENOLEB HOTEL</title>
    <link rel="stylesheet" href="css/login_cadastro.css">
</head>
<body>

 <form method="post" class="wrapper">
       <?php 

if(isset($_SESSION['success'])) {
    echo "<p style='color:green;  text-align:center; background: #ffffff; border-radius:20px;'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
}
?>
    

    <h1>Login</h1>  
    
    <?php if(isset($error)) echo "<p style='color:red; text-align:center; background: #afafaf; border-radius:20px;'>$error</p>"; 
    ?>

    <div class="input-box">
        <input type="email" name="email" placeholder="Digite seu E-mail" required>
        <i class="fa-solid fa-user"></i>
    </div>

    <div class="input-box">
        <input type="password" name="password" placeholder="Digite sua senha" required>
        <i class="fa-solid fa-lock"></i>
    </div>

    <div class="remenber-forgot">
        <a href="reset_senha.php">Esqueceu sua Senha?</a>
    </div>

    <button type="submit" class="btn" name="login">Acessar</button>

    <div class="register-link">
        <p>Ainda não tem conta? <a href="cadastrar.php">Cadastrar</a></p>
    </div>

</form>
</body>
</html>