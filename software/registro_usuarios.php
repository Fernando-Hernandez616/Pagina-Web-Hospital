<?php
session_start();
require "funciones/conecta.php";
$con = conecta();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $curp = mysqli_real_escape_string($con, $_POST['curp']);
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $password = $_POST['password'];

    // Validar correo repetido
    $check = mysqli_query($con, "SELECT * FROM usuario WHERE correo='$correo'");
    if (mysqli_num_rows($check) > 0) {
        $error = "El correo ya está registrado";
    } else {

        $sql = "INSERT INTO usuario 
        (nombre, curp, sexo, edad, tipo_sangre, telefono, correo, contraseña, rol, especialidad)
        VALUES 
        ('$nombre', '$curp', '$sexo', '$edad', '$tipo_sangre', '$telefono', '$correo', '$password', 'Paciente', NULL)";

        if (mysqli_query($con, $sql)) {
            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            $error = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Paciente</title>

<style>
body {
    font-family: Arial;
    background:#f4f6f9;
    margin:0;
}

/* CONTENEDOR */
.container {
    width:400px;
    margin:50px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

h2 {
    text-align:center;
    color:#1565c0;
}

input, select {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:5px;
    border:1px solid #ccc;
}

button {
    width:100%;
    padding:10px;
    background:#1565c0;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:5px;
}

button:hover {
    background:#0d47a1;
}

.error {
    color:red;
    text-align:center;
}

.success {
    color:green;
    text-align:center;
}
</style>

</head>

<body>

<!-- HEADER -->
<?php include "includes/header.php"; ?>

<div class="container">

<h2>Registro de Paciente</h2>

<?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">

<input type="text" name="nombre" placeholder="Nombre completo" required>

<input type="text" name="curp" placeholder="CURP" required>

<select name="sexo" required>
    <option value="">Sexo</option>
    <option value="Masculino">Masculino</option>
    <option value="Femenino">Femenino</option>
</select>

<input type="number" name="edad" placeholder="Edad" required>

<select name="tipo_sangre" required>
    <option value="">Tipo de sangre</option>
    <option>A+</option>
    <option>A-</option>
    <option>B+</option>
    <option>B-</option>
    <option>AB+</option>
    <option>AB-</option>
    <option>O+</option>
    <option>O-</option>
</select>

<input type="text" name="telefono" placeholder="Teléfono" required>

<input type="email" name="correo" placeholder="Correo" required>

<input type="password" name="password" placeholder="Contraseña" required>

<button type="submit">Registrarse</button>

</form>

</div>

</body>
</html>

<?php mysqli_close($con); ?>