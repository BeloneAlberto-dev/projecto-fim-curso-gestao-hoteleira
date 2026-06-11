<?php
include 'db.php';

// ===== IMPORTAR PHPMailer (MANUAL) =====
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// ================== ETAPA 1: ENVIAR EMAIL ==================
if (isset($_POST['email'])) {

    $email = $conn->real_escape_string($_POST['email']);

    // NÃO permite admin
    $sql = "SELECT * FROM users WHERE email='$email' AND role != 'admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $conn->query("UPDATE users 
                      SET reset_token='$token', reset_expires='$expires' 
                      WHERE email='$email'");

        $link = "http://localhost/hotel/reset_senha.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            // ===== CONFIG SMTP (GMAIL) =====
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'enoleb1@gmail.com'; // MUDA AQUI
            $mail->Password = 'lipdesehvxtuxzpf';   // MUDA AQUI
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // ===== EMAIL =====
            $mail->setFrom('enoleb1@gmail.com', 'Seu Site');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperacao de senha Enoleb-Hotel';
            $mail->Body = "
                <h2>Recuperação de senha</h2>
                <p>Clique no botão abaixo para redefinir sua senha:</p>
                <a href='$link' style='padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;'>Redefinir senha</a>
                <p>Este link expira em 15 minutos.</p>
            ";

            $mail->send();

        } catch (Exception $e) {
            echo "Erro ao enviar email: {$mail->ErrorInfo}";
        }
    }

    echo "<p>Se o email estiver cadastrado, você receberá um link.</p>";
}


// ================== ETAPA 2: ATUALIZAR SENHA ==================
if (isset($_POST['password']) && isset($_POST['token'])) {

    $token = $conn->real_escape_string($_POST['token']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users 
            WHERE reset_token='$token' 
            AND reset_expires > NOW()
            AND role != 'admin'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $conn->query("UPDATE users 
                      SET password='$password',
                          reset_token=NULL,
                          reset_expires=NULL
                      WHERE reset_token='$token'");

        echo "<p>Senha atualizada com sucesso!</p>";

    } else {
        echo "<p>Token inválido ou expirado!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Recuperar senha</title>

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
    background: linear-gradient(135deg, var(--primary-color), #000);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CARD */
.container {
    background: var(--white-color);
    padding: 35px;
    border-radius: 20px;
    width: 100%;
    max-width: 380px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    animation: fadeIn 0.5s ease;
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
    margin-bottom: 6px;
}

/* INPUT */
input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin-bottom: 18px;
    outline: none;
    transition: 0.3s;
    font-size: 14px;
}

input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
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

/* MENSAGENS */
p {
    text-align: center;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: 500;
}

p[style*="red"] {
    background: #ffe5e5;
    color: red;
}

p[style*="green"] {
    background: #e6ffe6;
    color: green;
}

/* ANIMAÇÃO */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</head>

<body>

<div class="container">

<?php if (!isset($_GET['token'])): ?>

  <h2>Esqueci minha senha</h2>

  <form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Enviar link</button>
  </form>

<?php else: ?>

  <h2>Nova senha</h2>

  <form method="POST">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

    <label>Nova senha:</label>
    <input type="password" name="password" required>

    <button type="submit">Atualizar senha</button>
  </form>

<?php endif; ?>

</div>

</body>
</html>
