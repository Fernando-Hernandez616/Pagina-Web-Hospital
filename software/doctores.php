<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

// Obtener médicos
$sql = "SELECT * FROM usuario WHERE rol='Medico' LIMIT 5";
$res = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Doctores - Hospital Digital</title>

<style>

/* GENERAL */
body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f9;
}

/* CONTENEDOR */
.container {
    width: 88%;
    margin: 45px auto;
}

/* TÍTULO */
h2 {
    text-align: center;
    color: #1565c0;
    margin-bottom: 40px;
    font-size: 40px;
    font-weight: bold;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 30px;
}

/* TARJETAS */
.card {
    background: white;
    padding: 30px 20px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    text-align: center;
    transition: 0.3s;
    border: 1px solid #e5e5e5;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.12);
}

/* FOTO */
.avatar {
    width: 95px;
    height: 95px;
    border-radius: 50%;
    margin-bottom: 15px;
    object-fit: cover;
    background: #f1f1f1;
    padding: 5px;
}

/* NOMBRE */
.card h3 {
    color: #1565c0;
    margin: 10px 0;
    font-size: 28px;
}

/* TEXTO */
.card p {
    color: #555;
    font-size: 16px;
    margin: 10px 0;
    line-height: 1.5;
}

/* BOTÓN */
.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background: #1565c0;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 15px;
    transition: 0.3s;
}

.btn:hover {
    background: #0d47a1;
}

.btn:hover {
    background:#0d47a1;
}

</style>

</head>

<body>

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

<h2>Doctores Disponibles</h2>

<div class="grid">

<?php if (mysqli_num_rows($res) > 0): ?>

    <?php while ($d = mysqli_fetch_assoc($res)): ?>

        <div class="card">

            <!-- Imagen genérica -->
            <img src="https://cdn-icons-png.flaticon.com/512/3774/3774299.png" class="avatar">

            <h3><?php echo $d['nombre']; ?></h3>

            <p><strong>Especialidad:</strong><br>
            <?php echo $d['especialidad']; ?></p>

            <p><strong>Contacto:</strong><br>
            <?php echo $d['correo']; ?></p>

            <a class="btn" href="agendar_cita.php?id=<?php echo $d['id_usuario']; ?>">
                Agendar cita
            </a>

        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p>No hay doctores registrados.</p>
<?php endif; ?>

</div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

</body>
</html>

<?php mysqli_close($con); ?>