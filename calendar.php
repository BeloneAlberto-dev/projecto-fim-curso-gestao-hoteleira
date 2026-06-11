<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'db.php';

$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Calendário de Reservas</title>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

:root {
    --white-color:#fff;
    --dark-color:#252525;
    --primary-color:#3b1c1c;
    --secondary-color:#f3961c;
    --light-pink-color:#faf4f5;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;

    background:
        linear-gradient(
            rgba(20,10,10,0.78),
            rgba(20,10,10,0.82)
        ),

        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop');

    background-size:cover;
    background-position:center;
    background-attachment:fixed;

    overflow-x:hidden;
    color:var(--white-color);
    padding:40px 25px;
}

/* EFEITOS */

body::before{
    content:'';
    position:fixed;
    width:400px;
    height:400px;
    background:rgba(243,150,28,0.15);
    border-radius:50%;
    top:-100px;
    right:-100px;
    filter:blur(80px);
    z-index:-1;
}

body::after{
    content:'';
    position:fixed;
    width:350px;
    height:350px;
    background:rgba(255,255,255,0.04);
    border-radius:50%;
    bottom:-100px;
    left:-100px;
    filter:blur(80px);
    z-index:-1;
}

/* CONTAINER */

.container{
    max-width:1450px;
    margin:auto;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:35px;
    flex-wrap:wrap;
}

/* TÍTULO */

.title-box h1{
    font-size:52px;
    font-weight:700;
    line-height:1;
}

.title-box span{
    color:var(--secondary-color);
}

.title-box p{
    margin-top:12px;
    color:#e5e5e5;
    font-size:15px;
    letter-spacing:0.5px;
}

/* SELECT CARD */

.filter-card{
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.10);
    backdrop-filter:blur(20px);
    border-radius:24px;
    padding:18px 22px;
    min-width:300px;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.35),
        inset 0 1px 0 rgba(255,255,255,0.05);
}

.filter-card label{
    display:block;
    margin-bottom:8px;
    color:#d8d8d8;
    font-size:13px;
    letter-spacing:1px;
    text-transform:uppercase;
}

/* SELECT */

#tipoQuarto{
    width:100%;
    background:transparent;
    border:none;
    outline:none;
    color:var(--white-color);
    font-size:17px;
    font-weight:600;
    cursor:pointer;
}

#tipoQuarto option{
    background:var(--dark-color);
    color:white;
}

/* CARD CALENDÁRIO */

.calendar-wrapper{
    position:relative;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(25px);

    border-radius:40px;

    padding:35px;

    overflow:hidden;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.45),
        inset 0 1px 0 rgba(255,255,255,0.04);
}

/* GLOW */

.calendar-wrapper::before{
    content:'';
    position:absolute;
    width:350px;
    height:350px;
    background:rgba(243,150,28,0.12);
    border-radius:50%;
    top:-120px;
    right:-120px;
    filter:blur(70px);
}

.calendar-wrapper{
    position:relative;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.12);

    backdrop-filter:blur(25px);

    border-radius:40px;

    padding:35px;

    overflow:hidden;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.45),
        0 0 0 1px rgba(255,255,255,0.04) inset;
}
/* CALENDAR */

#calendar{
    position:relative;
    z-index:2;
}

/* TOOLBAR */

.fc .fc-toolbar{
    margin-bottom:30px !important;
}

.fc .fc-toolbar-title{
    color:var(--white-color);
    font-size:38px !important;
    font-weight:700;
}

/* BOTÕES */

.fc .fc-button{
    background:rgba(255,255,255,0.08) !important;

    border:1px solid rgba(255,255,255,0.08) !important;

    color:white !important;

    border-radius:16px !important;

    padding:11px 18px !important;

    font-size:14px !important;

    font-weight:600 !important;

    transition:0.35s !important;

    backdrop-filter:blur(10px);
}

.fc .fc-button:hover{
    background:linear-gradient(
        135deg,
        var(--secondary-color),
        #ff7a00
    ) !important;

    transform:translateY(-2px);

    box-shadow:0 10px 25px rgba(243,150,28,0.35);
}

.fc .fc-button-active{
    background:linear-gradient(
        135deg,
        var(--secondary-color),
        #ff7a00
    ) !important;
}

/* GRID */

.fc-theme-standard td,
.fc-theme-standard th{
    border:1px solid rgba(255,255,255,0.05);
}

/* HEADER DOS DIAS */

.fc-col-header-cell{
    background:linear-gradient(
        135deg,
        var(--secondary-color),
        #ffffff
    );

    padding:18px 0 !important;

    border:none !important;
}

.fc-col-header-cell-cushion{
    text-decoration:none !important;

    color:white !important;

    font-size:12px;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:1.5px;
}

/* DIAS */

.fc-daygrid-day{
    transition:0.3s;
}

.fc-daygrid-day:hover{
    background:rgba(255,255,255,0.03);
}

/* NÚMERO DOS DIAS */

.fc-daygrid-day-number{
    color:white !important;
    text-decoration:none !important;
    padding:14px !important;
    font-size:15px;
    font-weight:600;
}

/* DIA ATUAL */

.fc-day-today{
    background:rgba(243,150,28,0.12) !important;
}

/* EVENTOS */

.fc-event{
    border:none !important;

    border-radius:16px !important;

    padding:8px 12px !important;

    background:linear-gradient(
        135deg,
        #ff000021,
        #6b0000
    ) !important;

    font-size:12px !important;

    font-weight:700;

    letter-spacing:0.5px;

    box-shadow:
        0 8px 20px rgba(225,29,72,0.35),
        inset 0 1px 0 rgba(255,255,255,0.25);
}

/* GRID */

.fc-scrollgrid{
    border-radius:24px;
    overflow:hidden;
}

.fc-theme-standard .fc-scrollgrid{
    border:none !important;
}

/* RESPONSIVO */

@media(max-width:992px){

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    .title-box h1{
        font-size:38px;
    }

    .calendar-wrapper{
        padding:20px;
    }

    .fc .fc-toolbar{
        flex-direction:column;
        gap:18px;
    }

    .fc .fc-toolbar-title{
        font-size:28px !important;
    }

}

/* VIEW SEMANA */

.fc-timeGridWeek-view{
    border-radius:24px;
    overflow:hidden;
}

/* COLUNAS DA SEMANA */

.fc-timegrid-col{
    background:rgba(255,255,255,0.02);
    transition:0.3s;
}

.fc-timegrid-col:hover{
    background:rgba(255,255,255,0.04);
}

/* HORÁRIOS */

.fc-timegrid-slot{
    height:55px !important;
    border-color:rgba(255,255,255,0.05) !important;
}

.fc-timegrid-slot-label{
    color:#d4d4d4;
    font-size:12px;
    font-weight:500;
}

/* CABEÇALHO DA SEMANA */

.fc-timegrid-axis{
    background:rgba(255,255,255,0.04);
}

.fc-timegrid-axis-cushion{
    color:white !important;
}

.fc-timegrid-slot-label-cushion{
    color:#d4d4d4 !important;
}

/* TOPO DOS DIAS */

.fc-timegrid-col-frame{
    min-height:100%;
}

.fc-timegrid-col.fc-day-today{
    background:rgba(243,150,28,0.12) !important;
}

/* NÚMERO DO DIA */

.fc-timegrid-col .fc-daygrid-day-number{
    color:white !important;
    font-size:18px;
    font-weight:700;
}

/* EVENTOS NA SEMANA */

.fc-timegrid-event{
    border:none !important;

    background:linear-gradient(
        135deg,
        #ff4d6d,
        #e11d48
    ) !important;

    border-radius:18px !important;

    padding:8px !important;

    box-shadow:
        0 10px 25px rgba(225,29,72,0.35);

    overflow:hidden;
}

/* TEXTO EVENTO */

.fc-timegrid-event .fc-event-title{
    font-size:13px;
    font-weight:700;
}

.fc-timegrid-event .fc-event-time{
    font-size:11px;
    opacity:0.9;
}

/* LINHA AGORA */

.fc-timegrid-now-indicator-line{
    border-color:var(--secondary-color) !important;
    border-width:2px !important;
}

.fc-timegrid-now-indicator-arrow{
    border-color:var(--secondary-color) !important;
}

/* SCROLL SEMANA */

.fc-scroller{
    scrollbar-width:thin;
    scrollbar-color:var(--secondary-color) transparent;
}
</style>

</head>
<body>

<div class="container">

    <div class="header">

        <div class="title-box">
            <a href="#"  onclick="history.back()" style="text-decoration: none; color:#fff; cursor:pointer;"><h1>Hotel <span>Dayane</span></h1></a>
            <p>Painel moderno para controle de reservas e ocupações.</p>
        </div>

        <div class="filter-card">

            <label>Escolha o tipo de quarto</label>

            <select id="tipoQuarto">

                <?php while($room = $rooms_result->fetch_assoc()): ?>

                    <option value="<?= $room['tipo'] ?>">
                        <?= $room['tipo'] ?>
                    </option>

                <?php endwhile; ?>

            </select>

        </div>

    </div>

    <div class="calendar-wrapper">
        <div id="calendar"></div>
    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        locale: 'pt',

        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },

        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana'
        },

        events: async function(fetchInfo, successCallback) {

            const tipo = document.getElementById("tipoQuarto").value;

            const res = await fetch("reservas.php?tipo=" + tipo);

            const data = await res.json();

            const eventos = data.map(r => ({
                title: "Reservado",
                start: r.entrada,
                end: r.saida,
                color: "#ff0000"
            }));

            successCallback(eventos);
        }

    });

    calendar.render();

    document.getElementById("tipoQuarto").addEventListener("change", function () {
        calendar.refetchEvents();
    });

});

</script>

</body>
</html>
