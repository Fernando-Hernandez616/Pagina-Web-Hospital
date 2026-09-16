<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

// Solo médicos
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'Medico') {
    header("Location: login.php");
    exit;
}

$id_medico = $_SESSION['id_usuario'];

// Obtener citas del médico
$sql = "SELECT c.*, p.nombre AS paciente
        FROM citas_medicas c
        JOIN usuario p ON c.id_paciente = p.id_usuario
        WHERE c.id_medico = $id_medico
        ORDER BY c.fecha DESC";

$res = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Citas del Médico</title>

<style>
.container {
    width:80%;
    margin:30px auto;
    background:white;
    padding:20px;
}

.card {
    display:flex;
    justify-content:space-between;
    padding:15px;
    border-bottom:1px solid #ddd;
}

.estado {
    padding:5px 10px;
    border-radius:5px;
    color:white;
    font-size:12px;
}

.pendiente { background:orange; }
.confirmada { background:green; }
.cancelada { background:red; }

button {
    padding:5px 10px;
    border:none;
    cursor:pointer;
    margin-left:5px;
}

.btn-confirmar { background:green; color:white; }
.btn-cancelar { background:red; color:white; }
</style>
</head>

<body>

<?php include "includes/header.php"; ?>

<div class="container">
<h2>Mis Citas (Médico)</h2>

<?php if (mysqli_num_rows($res) > 0): ?>
    
    <?php while ($c = mysqli_fetch_assoc($res)): ?>
        
        <?php $clase = strtolower($c['estado']); ?>

        <div class="card">
            <div>
                <strong>Paciente:</strong> <?php echo $c['paciente']; ?><br>
                <strong>Fecha:</strong> <?php echo $c['fecha']; ?>
            </div>

            <div>
                <span class="estado <?php echo $clase; ?>">
                    <?php echo $c['estado']; ?>
                </span>
            </div>
        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p>No hay citas asignadas.</p>
<?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>

</body>
</html>

<?php mysqli_close($con); ?>