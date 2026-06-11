<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

            $sql = "INSERT INTO reservas 
                    (id_client, tipo, entrada, saida, data_reserva)
                    VALUES 
                    ('$id_client', '$tipo', '$entrada', '$saida', NOW())";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success'] = "Check-in realizado com sucesso!";
                header("Location: usuario_logado.php");
                exit();
            } else {
                $error = "Erro ao registrar reserva: " . $conn->error;
            }
        }
    }
}

//buscar tipos de quartos disponiveis
$rooms_sql = "SELECT * FROM rooms"; 
$rooms_result = $conn->query($rooms_sql); 

//buscar de quartos
$rooms_result1 = $conn->query("SELECT * FROM rooms"); 

//buscar reservas para ususario
$id_client = $_SESSION['user_id'];


$sql = "SELECT * FROM reservas
        WHERE id_client = '$id_client'
        ORDER BY data_reserva DESC";

$result2 = $conn->query($sql);

$result = $conn->query("SELECT * FROM users WHERE id_client=$id_client");
$user = $result->fetch_assoc();
?>  

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
     <!-- Link Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css">
    <!-- Link css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Hotel_Dayane</title>

    <style>
    
    </style>
</head>
<body>
    <!-- Header/Navbar -->
    <header>
        <nav class="navbar section-content">
            <a href="#" class="nav-logo">
                <h2 class="logo-text">🏤HOTEL Dayane</h2>
            </a>
            <ul class="nav-menu">
                <button id="menu-close-button" class="fas fa-times"></button>
                <li class="nav-item">
                    <a href="#" class="nav-link">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="#reservas" class="nav-link">Minhas Reservas</a>
                </li>
                <li class="nav-item">
                    <a href="#rooms" class="nav-link">Quartos</a>
                </li>
                <li class="nav-item">
                    <a href="calendar.php" class="nav-link">Calendario</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Contacto</a>
                </li>
                   <div class="buttons">
                        <a href="logout.php" class="button LOGIN-CADASTRO">Sair&nbsp;<i class="fa-solid fa-right-from-bracket"></i></a>
                    </div>
            </ul>
               <button id="menu-open-button" class="fas fa-bars"></button>
        </nav>
    </header>
    <!-- Hero-Section -->
        <section class="hero-section">
            <div class="section-content">
                <div class="hero-details">
                    <h2 class="title">HOTEL Dayane</h2>
                    <h3 class="subtitle">Temos os melhores serviços de reservas de quartos para você e sua família</h3>
                    <p class="description">
                        No HOTEL Dayane você encontra tudo que você procura desde a reserva até aos serviços e atendimento de quartos 
                    </p>
                    <div class="buttons">
                        <a href="#minhas_reservas" class="button contact-us">MINHAS RESERVAS</a>
                    </div>
                </div>
                <div class="contact-section" style="background-color: #3b1c1c; margin-top:90px;">

                 <form class="contact-form" style="max-width:1000px; " method="post">
    <?php 
if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>";
if(isset($error1)) echo "<p style='color:red; text-align:center;'>$error1</p>";
if(isset($_SESSION['success'])) {
    echo "<p style='color:green;  text-align:center;'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
}
?>                  
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
                    <label>Data de Saida</label>
                         <input type="date" name="saida" min="<?= date('Y-m-d') ?>" class="form-input" required>
                        <button type="submit" class="submit-button" style="background: #f3961c;" >RESERVAR</button>
                    </form>
                </div>
            </div>
        </section>
         <!-- MInhas reservas Section -->
          <section class="reserva-section" id="reservas">
            <h2 class="section-title">Minhas Reservas</h2>
            <div class="section-content">
                <table>
          <?php 
if(isset($error2)) echo "<p style='color:red; text-align:center;'>$error</p>";?>              
    <tr>
        <th>Tipo</th>
        <th>Entrada</th>
        <th>Saída</th>
        <th>Data da Reserva</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>

<?php while($row = $result2->fetch_assoc()): ?>
<tr>
    <td><?= $row['tipo'] ?></td>
    <td><?= $row['entrada'] ?></td>
    <td><?= $row['saida'] ?></td>
    <td><?= $row['data_reserva'] ?></td>
    <td><?= $row['status'] ?></td>
     <td>
                        <a href="edit_reserva_user.php?id_reserva=<?= $row['id_reserva'] ?>&tabela=reservas"><i class="fa-solid fa-pen-to-square"></i></a> |
                        <a href="delete_reserva.php?id_reserva=<?= $row['id_reserva'] ?>&tabela=reservas" onclick="return confirm('Deseja apagar este quarto?')"><i class="fa-solid fa-trash-can"></i></a> |
                        <a href="#?id_reserva=<?= $row['id_reserva'] ?>&tabela=reservas">Pagar</a>
                    </td>
</tr>
<?php endwhile; ?>
</table>
    </div>
     </section>
      <!-- Rooms Section -->
          <section class="room-section" id="rooms">
            <h2 class="section-title">Quartos</h2>
            <div class="section-content">
                
                <ul class="room-list">
                   <?php while($room = $rooms_result1->fetch_assoc()): ?>
                    <li class="room-item" >
                        <img src="<?= $room['imagem'] ?>" alt="Room1" class="room-image">
                       <h1 class="subititle">Quarto  <?= $room['tipo'] ?></h1>
                       <p class="text" style="margin-bottom: 10px;"><?= $room['descricao'] ?></p>
                            <h3><?= $room['preco'] ?> Kz </h3>
                    </li>
                    <?php endwhile; ?>
                </ul>
                
            </div>
          </section>
                    <!-- Contact Section -->
                <section class="contact-section" id="contact">
                <h2 class="section-title">Contacte-nos</h2>
                <div class="section-content">
                    <ul class="contact-info-list">
                       <li class="contact-info">
    <i class="fa-solid fa-location-crosshairs"></i>

  <a href="#" onclick="mostrarMapa(); return false;">Ver localização</a>

    <div id="mapa" style="display: none; margin-top: 10px;">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.6262189643185!2d13.362007000070067!3d-8.914297392266345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a51f825def0dce3%3A0xd04892316a16d890!2sHotel%20Dayane!5e0!3m2!1spt-PT!2sao!4v1776515410238!5m2!1spt-PT!2sao"
            width="100%" 
            height="300" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</li>
                        <li class="contact-info">
                            <i class="fa-regular fa-envelope"></i>
                            <p>info@dayane.com</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-phone"></i>
                            <p>(+244) 922983498</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-regular fa-clock"></i>
                            <p>Sempre disponível(24/24)</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-globe"></i>
                            <p>www.hotel_dayane.com</p>
                        </li>
                    </ul>
                    <form action="contactos.php" method="POST" class="contact-form">
     <?php 
if(isset($_SESSION['success1'])) {
    echo "<p style='color:green;  text-align:center;'>".$_SESSION['success1']."</p>";
    unset($_SESSION['success1']);
}
if(isset($_SESSION['error1'])) {
    echo "<p style='color:red;  text-align:center;'>".$_SESSION['error1']."</p>";
    unset($_SESSION['error1']);
}
?>   
                        <input type="text" name="nome" placeholder="Seu nome"  class="form-input" value="<?= $user['name'] ?>" required>
                        <input type="email" name="email" placeholder="Seu email"   class="form-input" value="<?= $user['email'] ?>" required>
                        <textarea name="mensagem" placeholder="Sua mensagem"  class="form-input"></textarea>
                        <button class="submit-button">Enviar</button>
                    </form>
                </div>
             </section>
            </main>
            <!-- Footer -->
               <footer class="footer-section">
                <div class="section-content">
                    <p class="copyrigth-text">&copy; 2026 HOTEL Dayane</p>
                     <div class="social-link-list">
                        <a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-x"></i></a>
                    </div>
                    <p class="policy-text">
                        <a href="#" class="policy-link">Privacy policy</a>
                        <span class="separator">&bull;</span>
                        <a href="#" class="policy-link">Refund policy</a>
                    </p>
                </div>
              </footer>
    <!-- Link Swiper Script-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
     <!-- Link Custom Script -->
    <script src="js/script.js"></script>
</body>
</html>