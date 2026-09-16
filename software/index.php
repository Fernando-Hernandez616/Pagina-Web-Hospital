<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

// FORZAR RECARGA AL INICIAR/CERRAR SESIÓN
if (isset($_GET['reload']) || isset($_GET['logout'])) {

    echo "<script>
            window.history.replaceState({}, document.title, 'index.php');
          </script>";
}

// VALIDAR SI ESTÁ LOGUEADO
$logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// ROL
$rol = $logueado ? $_SESSION['rol'] : '';

// MOSTRAR MODAL LOGIN
$mostrarModal = false;

if (isset($_GET['login'])) {
    $mostrarModal = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Hospital Digital</title>

<style>

/* GENERAL */
body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f9;
}

/* CONTENEDOR */
.container {
    width: 85%;
    margin: auto;
}

/* BUSCADOR */
.search-container {
    text-align: center;
    margin: 40px 0;
}

.search-container input {
    padding: 14px;
    width: 500px;
    border-radius: 20px 0 0 20px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 15px;
}

.search-container button {
    padding: 14px 24px;
    border: none;
    background: #1565c0;
    color: white;
    border-radius: 0 20px 20px 0;
    cursor: pointer;
    font-size: 15px;
}

/* TARJETAS */
.results-container {
    background: white;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.results-container h2 {
    color: #1565c0;
    margin-top: 0;
}

.card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border-bottom: 1px solid #eee;
}

.card:last-child {
    border-bottom: none;
}

/* IZQUIERDA */
.card-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

/* IMAGEN */
.doctor-img {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1565c0;
}

/* INFO */
.doctor-info strong {
    color: #1565c0;
    font-size: 18px;
}

.doctor-info {
    color: #444;
    font-size: 15px;
}

/* BOTONES */
.btn {
    background: #1565c0;
    color: white;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.btn:hover {
    background: #0d47a1;
}

/* LOGO */
.logo {
    position: absolute;
    top: 12px;
    left: 15px;
    height: 50px;
    border-radius: 8px;
}

/* MODAL */
.modal-login {
    display: <?php echo $mostrarModal ? 'flex' : 'none'; ?>;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

/* CONTENIDO MODAL */
.modal-content {
    background: white;
    padding: 30px;
    width: 350px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.modal-content h2 {
    color: #1565c0;
    margin-top: 0;
}

.modal-content p {
    font-size: 16px;
    color: #444;
    margin: 20px 0;
}

.modal-btn {
    background: #1565c0;
    color: white;
    padding: 12px 22px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
}

.modal-btn:hover {
    background: #0d47a1;
}

</style>
</head>

<body>

<!-- LOGO -->
<img src="img/logo.jpg" class="logo">

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

    <!-- BUSCADOR -->
    <div class="search-container">

        <form method="GET">

<?php
// PLACEHOLDER SEGÚN ROL

$placeholder = "Buscar doctores...";

if ($logueado && $rol == "Medico") {
    $placeholder = "Buscar pacientes...";
}
?>

            <input type="text"
                   name="query"
                   placeholder="<?php echo $placeholder; ?>">

            <button type="submit">Buscar</button>

        </form>

    </div>

<?php

// ===============================
// BUSCADOR
// ===============================

if (!empty($_GET['query'])) {

    $busqueda = mysqli_real_escape_string($con, $_GET['query']);

    // SI ES MÉDICO → BUSCAR PACIENTES
    if ($logueado && $rol == "Medico") {

        $sql = "SELECT * FROM usuario
                WHERE rol='Paciente'
                AND nombre LIKE '%$busqueda%'";

        $res = mysqli_query($con, $sql);

        echo "<div class='results-container'>";
        echo "<h2>Pacientes encontrados</h2>";

        if (mysqli_num_rows($res) > 0) {

            while ($p = mysqli_fetch_assoc($res)) {

                echo "<div class='card'>

                        <div class='card-left'>

                            <img src='img/paciente_default.png'
                                 class='doctor-img'>

                            <div class='doctor-info'>
                                <strong>".$p['nombre']."</strong><br>
                                Paciente
                            </div>

                        </div>

                      </div>";
            }

        } else {

            echo "<p>No se encontraron pacientes.</p>";
        }

        echo "</div>";

    } else {

        // VISITANTE O PACIENTE → BUSCAR DOCTORES

        $sql = "SELECT * FROM usuario
                WHERE rol='Medico'
                AND nombre LIKE '%$busqueda%'";

        $res = mysqli_query($con, $sql);

        echo "<div class='results-container'>";
        echo "<h2>Doctores encontrados</h2>";

        if (mysqli_num_rows($res) > 0) {

            while ($d = mysqli_fetch_assoc($res)) {

                $foto = "img/doctor_default.jpg";

                if (isset($d['foto']) && !empty($d['foto'])) {
                    $foto = $d['foto'];
                }

                echo "<div class='card'>

                        <div class='card-left'>

                            <img src='".$foto."'
                                 class='doctor-img'>

                            <div class='doctor-info'>
                                <strong>".$d['nombre']."</strong><br>
                                ".$d['especialidad']."
                            </div>

                        </div>

                        <div>";

                if ($logueado) {

                    echo "<a class='btn'
                               href='agendar_cita.php?id=".$d['id_usuario']."'>
                               Agendar
                          </a>";

                } else {

                    echo "<a class='btn'
                               href='index.php?login=1'>
                               Agendar
                          </a>";
                }

                echo "</div>
                      </div>";
            }

        } else {

            echo "<p>No se encontraron doctores.</p>";
        }

        echo "</div>";
    }
}


// ===============================
// MOSTRAR DOCTORES
// ===============================

$sql = "SELECT * FROM usuario WHERE rol='Medico' LIMIT 6";
$res = mysqli_query($con, $sql);

echo "<div class='results-container'>";
echo "<h2>Médicos disponibles</h2>";

while ($d = mysqli_fetch_assoc($res)) {

    $foto = "img/doctor_default.jpg";

    if (isset($d['foto']) && !empty($d['foto'])) {
        $foto = $d['foto'];
    }

    echo "<div class='card'>

            <div class='card-left'>

                <img src='".$foto."'
                     class='doctor-img'
                     alt='Doctor'>

                <div class='doctor-info'>
                    <strong>".$d['nombre']."</strong><br>
                    ".$d['especialidad']."
                </div>

            </div>

            <div>";

    if ($logueado) {

        echo "<a class='btn'
                   href='agendar_cita.php?id=".$d['id_usuario']."'>
                   Agendar
              </a>";

    } else {

        echo "<a class='btn'
                   href='index.php?login=1'>
                   Agendar
              </a>";
    }

    echo "</div>
          </div>";
}

echo "</div>";

mysqli_close($con);
?>

</div>

<!-- MODAL LOGIN -->
<div class="modal-login" id="modalLogin">

    <div class="modal-content">

        <h2>Debes iniciar sesión</h2>

        <p>
            Necesitas iniciar sesión para poder agendar una cita.
        </p>

        <button class="modal-btn" onclick="cerrarModal()">
            Aceptar
        </button>

    </div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

<script>

function cerrarModal() {

    window.location.href = "index.php";
}

</script>

</body>
</html>