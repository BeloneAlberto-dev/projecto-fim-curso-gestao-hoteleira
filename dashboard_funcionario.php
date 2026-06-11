<?php 
session_start(); 
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'funcionario'){     
    header("Location: login.php");     
    exit();
}  

include 'db.php';  

$users_result = $conn->query("SELECT * FROM users");  
$rooms_result = $conn->query("SELECT * FROM rooms"); 


$sql = "SELECT reservas.*, users.name, users.email
        FROM reservas
        JOIN users ON reservas.id_client = users.id_client
        ORDER BY reservas.data_reserva DESC";
$result = $conn->query($sql);

$adm_result = $conn->query("SELECT * FROM adm ORDER BY data_reserva DESC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Hotel_Dayane-Admin_Dashboard</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
    --white-color:#fff;
    --dark-color:#252525;
    --primary-color:#3b1c1c;
    --secondary-color:#f3961c;
    --light-pink-color:#faf4f5;
}

/* BODY */
body{
    margin:0;
    font-family:Arial;
    background: var(--light-pink-color);
    padding:15px;
}

/* HEADER */
.header{
    background: var(--primary-color);
    color:#fff;
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-radius:20px;
}

.logout-btn{
    display:flex;
    align-items:center;
    gap:8px;
    background: var(--secondary-color);
    color:#fff;
    padding:10px 16px;
    border-radius:12px;
    text-decoration:none;
}

.logout-btn:hover{
    background:#d87f0f;
}

/* LAYOUT */
.layout{
    display:flex;
    margin-top:15px;
}

aside{
    width:220px;
    background: var(--primary-color);
    padding:20px;
    border-radius:20px;
}

aside a{
    display:block;
    padding:12px;
    color:#fff;
    border-radius:8px;
    margin-bottom:8px;
    cursor:pointer;
}

aside a:hover{
    background: var(--secondary-color);
}

main{
    flex:1;
    padding:30px;
    background: var(--white-color);
    border-radius:25px;
    margin-left:15px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:15px;
}

.card{
    padding:22px;
    border-radius:20px;
    cursor:pointer;
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    background: var(--light-pink-color);
    transform:translateY(-5px);
    border-left:5px solid var(--secondary-color);
}

/* SECTIONS */
.dashboard-section{
    display:none;
    margin-top:25px;
}

.dashboard-section.active{
    display:block;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    text-align:center;
}

th{
    background: var(--secondary-color);
    color:#fff;
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#fafafa;
}

/* ACTION BUTTONS */
.actions a{
    display:inline-flex;
    justify-content:center;
    align-items:center;
    width:38px;
    height:38px;
    border-radius:12px;
    color:#fff;
    margin:0 4px;
}

.btn-edit{
    background: linear-gradient(135deg, #4facfe, #007bff);
}

.btn-delete{
    background: linear-gradient(135deg, #ff6a6a, #dc3545);
}

.actions a:hover{
    transform:scale(1.1);
}

/* ADD BUTTON */
.add-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color:#fff;
    padding:10px 16px;
    border-radius:12px;
    text-decoration:none;
    margin-bottom:10px;
}

.add-btn:hover{
    opacity:0.9;
    transform:translateY(-2px);
}
/* SEARCH */
.search-box{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:12px;
    margin:15px 0;
    font-size:15px;
    outline:none;
}

.search-box:focus{
    border-color: var(--secondary-color);
    box-shadow:0 0 8px rgba(243,150,28,0.3);
}
</style>
</head>

<body>

<header class="header">
    <h2>HOTEL Dayane</h2>
    <a href="logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</header>

<div class="layout">

<aside>
    <a onclick="showSection('reservas')">Reservas</a>
    <a onclick="showSection('quartos')">Quartos</a>
    <a href="calendar.php" style="text-decoration: none;">Calendario</a>
</aside>

<main>

    <div class="cards">
    <div class="card" onclick="showSection('reservas')">
        <h3>Reservas</h3>
        <p><?= $result->num_rows + $adm_result->num_rows ?></p>
    </div>
    <div class="card" onclick="showSection('quartos')">
        <h3>Quartos</h3>
        <p><?= $rooms_result->num_rows ?></p>
    </div>
</div>

<!-- QUARTOS -->
<section id="quartos" class="dashboard-section">
<h2>Quartos</h2>

<a href="add_room.php" class="add-btn">
<i class="fa-solid fa-bed"></i> Adicionar Quarto
</a>
<input type="text" 
       class="search-box" 
       id="searchRooms" 
       placeholder="Pesquisar quarto por tipo..."
       onkeyup="filterTable('searchRooms','roomsTable')">
<table id="roomsTable">
<tr><th>ID</th><th>Tipo</th><th>Descrição</th><th>Preço</th><th>Ações</th></tr>
<?php while($room = $rooms_result->fetch_assoc()): ?>
<tr>
<td><?= $room['id_rooms'] ?></td>
<td><?= $room['tipo'] ?></td>
<td><?= $room['descricao'] ?></td>
<td><?= $room['preco'] ?> Kz</td>
<td class="actions">
<a class="btn-edit" href="edit_room.php?id_rooms=<?= $room['id_rooms'] ?>">
<i class="fa-solid fa-pen-to-square"></i>
</a>
<a class="btn-delete" href="delete_room.php?id_rooms=<?= $room['id_rooms'] ?>" onclick="return confirm('Tem certeza que deseja apagar este quarto?')">
<i class="fa-solid fa-trash-can"></i>
</a>
</td>
</tr>
<?php endwhile; ?>
</table>
</section>

<!-- RESERVAS -->
<section id="reservas" class="dashboard-section">
<h2>Reservas</h2>

<a href="add_reserva.php" class="add-btn">
<i class="fa-solid fa-calendar-plus"></i> Nova Reserva
</a>
<input type="text" 
       class="search-box" 
       id="searchReservas" 
       placeholder="Pesquisar reserva por nome ou tipo..."
       onkeyup="filterTable('searchReservas','reservasTable')">
<table id="reservasTable">
<tr><th>Data-Reserva</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Entrada</th><th>Saída</th><th>Status</th><th>Ações</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['data_reserva'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['tipo'] ?></td>
<td><?= $row['entrada'] ?></td>
<td><?= $row['saida'] ?></td>
<td><?= $row['status'] ?></td>
<td class="actions">
<a class="btn-edit" href="edit_reserva_admin.php?id_reserva=<?= $row['id_reserva'] ?>&tabela=reservas">
<i class="fa-solid fa-pen-to-square"></i>
</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<br><br>

<h2>Reservas Admin</h2>
<input type="text" 
       class="search-box" 
       id="searchAdm" 
       placeholder="Pesquisar reserva admin..."
       onkeyup="filterTable('searchAdm','admTable')">
<table id="admTable">
<tr><th>Data-Reserva</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Entrada</th><th>Saída</th><th>Status</th><th>Ações</th></tr>
<?php while($adm = $adm_result->fetch_assoc()): ?>
<tr>
<td><?= $adm['data_reserva'] ?></td>
<td><?= $adm['name'] ?></td>
<td><?= $adm['email'] ?></td>
<td><?= $adm['tipo'] ?></td>
<td><?= $adm['entrada'] ?></td>
<td><?= $adm['saida'] ?></td>
<td><?= $adm['status'] ?></td>
<td class="actions">
<a class="btn-edit" href="edit_reserva_admin.php?id_reserva=<?= $adm['id_reserva'] ?>&tabela=adm">
<i class="fa-solid fa-pen-to-square"></i>
</a>
</td>
</tr>
<?php endwhile; ?>
</table>

</section>

</main>
</div>

<script>
function showSection(id){
    document.querySelectorAll('.dashboard-section').forEach(sec=>{
        sec.classList.remove('active');
    });

    document.getElementById(id).classList.add('active');
}

function filterTable(inputId, tableId){
    let input = document.getElementById(inputId);
    let filter = input.value.toLowerCase();

    let table = document.getElementById(tableId);
    let tr = table.getElementsByTagName("tr");

    for(let i = 1; i < tr.length; i++){

        let rowText = tr[i].textContent.toLowerCase();

        if(rowText.indexOf(filter) > -1){
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
</script>

</body>
</html>