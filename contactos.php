<?php
session_start();
include 'db.php';

/* verificar se o formulário foi enviado */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* receber dados */
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['mensagem']);

    /* validar campos */
    if (!empty($nome) && !empty($email) && !empty($mensagem)) {

        // Verifica quantas mensagens este email enviou hoje
        $check = $conn->prepare("
            SELECT COUNT(*) as total
            FROM contactos
            WHERE email = ?
            AND DATE(criado_em) = CURDATE()
        ");

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();
        $row = $result->fetch_assoc();

        if ($row['total'] >= 2) {

            $_SESSION['error1'] = "Você já enviou o limite máximo de 2 mensagens hoje.";

            if (isset($_SESSION['user_id'])) {
                header("Location: usuario_logado.php#contact");
            } else {
                header("Location: index.php#contact");
            }

            exit;
        }

        $check->close();

        /* preparar query */
        $sql = "INSERT INTO contactos (nome, email, mensagem)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("sss", $nome, $email, $mensagem);

            if ($stmt->execute()) {

                $_SESSION['success1'] = "Mensagem enviada com sucesso!";

                if (isset($_SESSION['user_id'])) {
                    header("Location: usuario_logado.php#contact");
                } else {
                    header("Location: index.php#contact");
                }

                exit;

            } else {
                $_SESSION['error1'] = "Erro ao enviar mensagem.";

                if (isset($_SESSION['user_id'])) {
                    header("Location: usuario_logado.php#contact");
                } else {
                    header("Location: index.php#contact");
                }

                exit;
            }

            $stmt->close();

        } else {
            $_SESSION['error1'] = "Erro na preparação da query.";

            if (isset($_SESSION['user_id'])) {
                header("Location: usuario_logado.php#contact");
            } else {
                header("Location: index.php#contact");
            }

            exit;
        }

    } else {

        $_SESSION['error1'] = "Preencha todos os campos.";

        if (isset($_SESSION['user_id'])) {
            header("Location: usuario_logado.php#contact");
        } else {
            header("Location: index.php#contact");
        }

        exit;
    }
}

$conn->close();
?>