<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "funciones/conecta.php";
$con = conecta();

// LOGIN PROCESO
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $correo = mysqli_real_escape_string($con, $_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuario WHERE correo='$correo' LIMIT 1";
    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) == 1) {

        $user = mysqli_fetch_assoc($res);

        if ($password == $user['contraseña']) {

            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['usuario'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            // RECARGAR INDEX AUTOMÁTICAMENTE
            header("Location: index.php?reload=" . time());
            exit;

        } else {

            $error = "Contraseña incorrecta";
        }

    } else {

        $error = "Usuario no encontrado";
    }
}

// ESTADO
$logueado = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if ($logueado) {
    $rol = $_SESSION['rol'];
    $usuario = $_SESSION['usuario'];
} else {
    $usuario = "visitante";
}
?>

<style>

/* HEADER */
header {
    background: #1565c0;
    color: white;
    padding: 20px 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

/* TITULO */
header h1 {
    margin: 0;
    font-size: 34px;
    font-weight: bold;
    letter-spacing: 1px;
}

/* LOGO */
.header-logo {
    position: absolute;
    left: 25px;
    width: 95px;
    height: 95px;
    object-fit: cover;
    border-radius: 12px;
}

/* DERECHA */
.header-actions {
    position: absolute;
    right: 25px;
    display: flex;
    align-items: center;
    gap: 15px;
}

/* BOTONES LOGIN */
.login-btn {
    background: white;
    color: #1565c0;
    padding: 10px 16px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

.login-btn:hover {
    background: #e3f2fd;
}

.register-btn {
    background: #0d47a1;
    color: white;
    padding: 10px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
}

.register-btn:hover {
    background: #08306b;
}

/* USUARIO */
.user-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* FOTO */
.user-photo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
}

/* INFO */
.user-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.user-name {
    margin-top: 8px;
    font-weight: bold;
    font-size: 15px;
}

/* BOTONES */
.user-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.profile-btn,
.logout-btn {
    padding: 8px 14px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
    font-size: 13px;
    text-align: center;
}

/* PERFIL */
.profile-btn {
    background: white;
    color: #1565c0;
}

.profile-btn:hover {
    background: #e3f2fd;
}

/* LOGOUT */
.logout-btn {
    background: #d32f2f;
    color: white;
}

.logout-btn:hover {
    background: #b71c1c;
}

/* NAV */
nav {
    background: #1976d2;
    padding: 14px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

nav a {
    color: white;
    margin: 0 14px;
    text-decoration: none;
    font-weight: bold;
}

nav a:hover {
    color: #dbeeff;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    background: white;
    width: 320px;
    margin: 8% auto;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
}

.close {
    float: right;
    font-size: 22px;
    cursor: pointer;
}

.modal input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.modal button {
    width: 100%;
    padding: 12px;
    background: #1565c0;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.modal button:hover {
    background: #0d47a1;
}

.link-register {
    display: block;
    margin-top: 14px;
    color: #1565c0;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
}

.error {
    color: red;
    font-size: 14px;
}

</style>

<header>

    <img src="img/logo.jpg" class="header-logo">

    <h1>Hospital Digital</h1>

    <div class="header-actions">

        <?php if (!$logueado): ?>

            <button class="login-btn" onclick="abrirLogin()">
                Iniciar sesión
            </button>

            <a href="registro_usuarios.php" class="register-btn">
                Registrarse
            </a>

        <?php else: ?>

            <div class="user-container">

                <div class="user-info">

                    <img src="img/perfil.jpg" class="user-photo">

                    <div class="user-name">
                        <?php echo htmlspecialchars($usuario); ?>
                    </div>

                </div>

                <div class="user-buttons">

                    <a href="perfil_paciente.php" class="profile-btn">
                        Mi perfil
                    </a>

                    <a href="logout.php" class="logout-btn">
                        Cerrar sesión
                    </a>

                </div>

            </div>

        <?php endif; ?>

    </div>

</header>

<nav>

    <a href="index.php">Inicio</a>

    <?php if (!$logueado): ?>

        <a href="servicios.php">Servicios</a>
        <a href="doctores.php">Doctores</a>
        <a href="contacto.php">Contacto</a>

    <?php else: ?>

        <?php if ($rol == 'Paciente'): ?>

            <a href="mis_citas.php">Mis Citas</a>
            <a href="agendar_cita.php">Agendar Cita</a>
            <a href="historial.php">Historial</a>
            <a href="recetas.php">Recetas</a>

        <?php endif; ?>

        <?php if ($rol == 'Medico'): ?>

            <a href="citas_medico.php">Citas</a>
            <a href="pacientes.php">Pacientes</a>
            <a href="recetas_medico.php">Recetas</a>
            <a href="historiales.php">Historiales</a>

        <?php endif; ?>

    <?php endif; ?>

</nav>

<div id="loginModal" class="modal">

    <div class="modal-content">

        <span class="close" onclick="cerrarLogin()">&times;</span>

        <h2>Iniciar Sesión</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">

            <input type="email"
                   name="correo"
                   placeholder="Correo"
                   required>

            <input type="password"
                   name="password"
                   placeholder="Contraseña"
                   required>

            <button type="submit" name="login">
                Entrar
            </button>

        </form>

        <a href="registro_usuarios.php" class="link-register">
            ¿No tienes cuenta? Regístrate
        </a>

    </div>

</div>

<script>

function abrirLogin() {
    document.getElementById("loginModal").style.display = "block";
}

function cerrarLogin() {
    document.getElementById("loginModal").style.display = "none";
}

window.onclick = function(event) {

    let modal = document.getElementById("loginModal");

    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>