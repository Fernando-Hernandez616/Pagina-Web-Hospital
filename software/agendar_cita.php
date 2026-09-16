<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

$logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Médicos
$sql_medicos = "SELECT * FROM usuario WHERE rol='Medico'";
$res_medicos = mysqli_query($con, $sql_medicos);

// Guardar cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!$logueado) {
        echo "<script>alert('Debes iniciar sesión'); window.location='index.php';</script>";
        exit;
    }

    $id_medico = $_POST['medico'];
    $fecha = $_POST['fecha'];
    $id_paciente = $_SESSION['id_usuario'];

    $sql = "INSERT INTO citas_medicas (fecha, estado, id_medico, id_paciente)
            VALUES ('$fecha', 'Pendiente', '$id_medico', '$id_paciente')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Cita agendada correctamente'); window.location='mis_citas.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agendar Cita</title>

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
    margin: 40px auto;
}

/* CARD PRINCIPAL */
.form-card {
    background: white;
    max-width: 500px;
    margin: auto;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* TITULO */
.form-card h2 {
    text-align: center;
    color: #1565c0;
}

/* INPUTS */
.form-card input,
.form-card select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* BOTON */
.btn {
    width: 100%;
    padding: 12px;
    background: #1565c0;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
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

</style>
</head>

<body>

<!-- LOGO -->
<img src="img/logo.jpg" class="logo">

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

    <div class="form-card">
        <h2>Agendar Cita</h2>

        <form method="POST">

            <label>Médico</label>
            <select name="medico" required>
                <option value="">Selecciona un médico</option>

                <?php while($m = mysqli_fetch_assoc($res_medicos)): ?>
                    <option value="<?php echo $m['id_usuario']; ?>">
                        <?php echo $m['nombre']." - ".$m['especialidad']; ?>
                    </option>
                <?php endwhile; ?>

            </select>

            <label>Fecha y hora</label>
            <input type="datetime-local" name="fecha" required>

            <button class="btn">Agendar Cita</button>

        </form>
    </div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

</body>
</html>

<?php mysqli_close($con); ?>