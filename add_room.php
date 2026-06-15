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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    if(isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {
        $imagem = "img/".$_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
    }else{
        $imagem = "";
    }
    $sql = "INSERT INTO rooms (tipo, descricao, preco, imagem)
            VALUES ('$tipo', '$descricao', '$preco', '$imagem')";

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

?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Criar Quarto</title>

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
    height: 100vh;
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
select, textarea, input {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid var(--medium-gray-color);
    outline: none;
    transition: 0.3s;
}

textarea {
    resize: none;
    min-height: 100px;
}

select:focus, textarea:focus, input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 6px rgba(243,150,28,0.4);
}

/* ===== BUTTON ===== */
button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: 8px;
    background: var(--secondary-color);
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: var(--primary-color);
}

/* ===== BACK LINK ===== */
.back-link {
    display: block;
    text-align: center;
    margin-top: 18px;
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
  <h2>Criar Quarto</h2>

  <form method="POST"  enctype="multipart/form-data">
     <?php
  if(isset($_SESSION['success'])) {
    echo "<p style='color:green;  text-align:center;'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
    }?> 

    <label>Tipo de Quarto</label>
    <select name="tipo">
        <option value="executivo">Executivo</option>
        <option value="espacoso">Espaçoso</option>
        <option value="suite">Suíte</option>
        <option value="simples">Simples</option>
        <option value="duplo">Duplo</option>
        <option value="triplo">Triplo</option>
        <option value="executivo2">Executivo-2</option>
        <option value="espacoso2">Espaçoso-2</option>
        <option value="suite2">Suíte-2</option>
        <option value="simples2">Simples-2</option>
        <option value="duplo2">Duplo-2</option>
        <option value="triplo2">Triplo-2</option>
        </select>

    <label>Descrição</label>
    <textarea name="descricao" placeholder="Descrição do quarto"></textarea>

    <label>Preço (Kz)</label>
    <input type="number" name="preco" placeholder="Preço" required step="0.01">

    <label>Selecionar imagem</label>
    <input type="file" name="imagem" accept="image/*" required>

    <button type="submit">Salvar Quarto</button>

  </form>

   <a href="javascript:history.back()" class="back-link">← Voltar</a>
</div>

</body>
</html>