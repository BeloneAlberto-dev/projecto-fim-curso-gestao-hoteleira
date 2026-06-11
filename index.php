<?php 
include 'db.php';  
$rooms_result = $conn->query("SELECT * FROM rooms"); 
$testemunho_result = $conn->query("SELECT * FROM testemunho"); 
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
    <title>Document</title>
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
                    <a href="#about" class="nav-link">Sobre</a>
                </li>
                <li class="nav-item">
                    <a href="#rooms" class="nav-link">Quartos</a>
                </li>
                <li class="nav-item">
                    <a href="#tst" class="nav-link">Avaliações</a>
                </li>
                <li class="nav-item">
                    <a href="#gallery" class="nav-link">Galeria</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Contacto</a>
                </li>
                   <div class="buttons">
                        <a href="login.php" class="button LOGIN-CADASTRO">Login/Cadastrar</a>
                    </div>
            </ul>
               <button id="menu-open-button" class="fas fa-bars"></button>
        </nav>
    </header>

    <main>
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
                        <a href="login.php" class="button book-now">Reservar Agora</a>
                        <a href="#contact" class="button contact-us">Contacte-nos</a>
                    </div>
                </div>
                <div class="hero-image-wrapper">
                    <img src="img/dayane.png" alt="" class="hero-image">
                </div>
            </div>
        </section>
        <!-- About Section -->
        <section class="about-section" id="about">
            <div class="section-content">
                <div class="about-imag-wrapper">
                    <img src="img/dayane-b.jpg" alt="" class="about-image">
                </div>
                <div class="about-details">
                    <h2 class="section-title">Sobre Nós</h2>
                    <p class="text">Somos uma empresa dedica para apresentar serviço de melhor qualidade aos nossos clientes garantindo assim uma boa experiência e uma história inesquecível de sua viagem.
                    Aqui você e sua familia vivem uma nova história. <br> <br>Oferecemos experiências únicas com conforto, segurança e qualidade.
                    </p>
                    <div class="social-link-list">
                        <a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="fa-brands fa-x"></i></a>
                    </div>
                </div>
            </div>
        </section>
         <!-- Rooms Section -->
          <section class="room-section" id="rooms">
            <h2 class="section-title">Quartos</h2>
            <div class="section-content">
                
                <ul class="room-list">
                    <?php while($room = $rooms_result->fetch_assoc()): ?>
                    <li class="room-item" >
                        <img src="<?= $room['imagem'] ?>" alt="Room1" class="room-image">
                       <h1 class="subititle">Quarto <?= $room['tipo'] ?></h1>
                       <p class="text" style="margin-bottom: 10px;"><?= $room['descricao'] ?></p>
                            <h3><?= $room['preco'] ?> Kz </h3>
                    </li>
                    <?php endwhile; ?>
                </ul>
                
            </div>
          </section>
            <!-- Testimonials Section -->
          <section class="testimonials-section" id="tst">
            <h2 class="section-title">Avaliações</h2>
            <div class="section-content">
                <div class="slider-container swiper">
                    <div class="slider-wrapper">
                        <ul class="testimonials-list swiper-wrapper">
                              <?php while($testemunho = $testemunho_result->fetch_assoc()): ?>
                            <li class="testimonial swiper-slide">
                                <img src="<?= $testemunho['imagem'] ?>" alt="" class="user-image">
                                <h3 class="name"><?= $testemunho['nome'] ?></h3>
                                <i class="feedback"><?= $testemunho['feedback'] ?></i>
                            </li>
                            <?php endwhile; ?>
                        </ul>

                         <div class="swiper-pagination"></div>
                         <div class="swiper-slide-button swiper-button-prev"></div>
                        <div class="swiper-slide-button swiper-button-next"></div>


                    </div>
                </div>
            </div>
          </section>
          <!-- Gallery Section -->
           <section class="gallery-section" id="gallery">
            <h2 class="section-title">Galeria</h2>
            <div class="section-content">
                <ul class="gallery-list">
                    <li class="gallery-item">
                        <img src="img/gallery1.jpg" alt="" class="gallery-image">
                    </li>
                    <li class="gallery-item">
                        <img src="img/gallery2.jpg" alt="" class="gallery-image">
                    </li>
                    <li class="gallery-item">
                        <img src="img/gallery3.jpg" alt="" class="gallery-image">
                    </li>
                    <li class="gallery-item">
                        <img src="img/gallery4.jpg" alt="" class="gallery-image">
                    </li>
                    <li class="gallery-item">
                        <img src="img/gallery5.jpg" alt="" class="gallery-image">
                    </li>
                    <li class="gallery-item">
                        <img src="img/gallery6.jpg" alt="" class="gallery-image">
                    </li>
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

                    <form action="contactos.php"  method="POST" class="contact-form">

                        <input type="text" name="nome" placeholder="Seu nome"  class="form-input" required>
                        <input type="email" name="email" placeholder="Seu email"   class="form-input" required>
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