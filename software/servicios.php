<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Servicios - Hospital Digital</title>

<style>

/* GENERAL */
body {
    margin:0;
    font-family: Arial;
    background:#f4f6f9;
}

/* CONTENEDOR */
.container {
    width:85%;
    margin:40px auto;
}

/* TÍTULO */
h2 {
    text-align:center;
    color:#1565c0;
    margin-bottom:30px;
}

/* GRID SERVICIOS */
.grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap:20px;
}

/* TARJETAS */
.card {
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
}

.card:hover {
    transform:translateY(-5px);
}

/* ICONO */
.icon {
    font-size:40px;
    margin-bottom:10px;
}

/* TÍTULO CARD */
.card h3 {
    color:#1565c0;
}

/* TEXTO */
.card p {
    font-size:14px;
    color:#555;
}

</style>

</head>

<body>

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

<h2>Nuestros Servicios</h2>

<div class="grid">

    <div class="card">
        <div class="icon">🩺</div>
        <h3>Consulta General</h3>
        <p>Atención médica básica para diagnóstico y tratamiento de enfermedades comunes.</p>
    </div>

    <div class="card">
        <div class="icon">❤️</div>
        <h3>Cardiología</h3>
        <p>Especialistas en el cuidado del corazón y enfermedades cardiovasculares.</p>
    </div>

    <div class="card">
        <div class="icon">🧠</div>
        <h3>Neurología</h3>
        <p>Diagnóstico y tratamiento de enfermedades del sistema nervioso.</p>
    </div>

    <div class="card">
        <div class="icon">👶</div>
        <h3>Pediatría</h3>
        <p>Atención médica especializada para niños y adolescentes.</p>
    </div>

    <div class="card">
        <div class="icon">🦷</div>
        <h3>Odontología</h3>
        <p>Cuidado dental integral: limpieza, tratamiento y prevención.</p>
    </div>

    <div class="card">
        <div class="icon">🧪</div>
        <h3>Laboratorio</h3>
        <p>Análisis clínicos para diagnóstico preciso y oportuno.</p>
    </div>

    <div class="card">
        <div class="icon">🏥</div>
        <h3>Hospitalización</h3>
        <p>Atención y cuidado para pacientes que requieren internamiento.</p>
    </div>

    <div class="card">
        <div class="icon">🚑</div>
        <h3>Urgencias</h3>
        <p>Servicio disponible 24/7 para emergencias médicas.</p>
    </div>

</div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

</body>
</html>