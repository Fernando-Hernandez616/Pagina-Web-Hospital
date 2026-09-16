<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'Paciente') {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// OBTENER HISTORIAL
$sql = "SELECT c.*, 
               m.nombre AS medico,
               m.especialidad,
               m.foto
        FROM citas_medicas c
        JOIN usuario m 
        ON c.id_medico = m.id_usuario
        WHERE c.id_paciente = $id_usuario
        ORDER BY c.fecha DESC";

$res = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial Médico</title>

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

/* TARJETA PRINCIPAL */
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
    margin-bottom: 20px;
}

/* CARD */
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

/* FOTO */
.doctor-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1565c0;
}

/* INFO */
.doctor-info {
    color: #444;
    font-size: 15px;
    line-height: 1.6;
}

.doctor-info strong {
    color: #1565c0;
    font-size: 18px;
}

/* ESTADOS */
.estado {
    padding: 10px 16px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    min-width: 110px;
}

.pendiente {
    background: orange;
}

.confirmada {
    background: #2e7d32;
}

.cancelada {
    background: #c62828;
}

/* SIN HISTORIAL */
.no-data {
    text-align: center;
    padding: 25px;
    color: #666;
    font-size: 16px;
}

</style>
</head>

<body>

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

    <div class="results-container">

        <h2>Historial Médico</h2>

        <?php if (mysqli_num_rows($res) > 0): ?>

            <?php while ($h = mysqli_fetch_assoc($res)): ?>

                <?php
                $foto = "img/doctor_default.jpg";

                if (!empty($h['foto'])) {
                    $foto = $h['foto'];
                }

                $clase = strtolower($h['estado']);
                ?>

                <div class="card">

                    <div class="card-left">

                        <img src="<?php echo $foto; ?>"
                             class="doctor-img"
                             alt="Doctor">

                        <div class="doctor-info">

                            <strong>
                                <?php echo $h['medico']; ?>
                            </strong>
                            <br>

                            <?php echo $h['especialidad']; ?>
                            <br>

                            <strong>Fecha:</strong>
                            <?php echo $h['fecha']; ?>
                            <br>

                            <strong>Consulta médica registrada</strong>

                        </div>

                    </div>

                    <div class="estado <?php echo $clase; ?>">
                        <?php echo $h['estado']; ?>
                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="no-data">
                No tienes historial médico registrado.
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

</body>
</html>

<?php mysqli_close($con); ?>