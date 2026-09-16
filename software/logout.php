<?php
session_start();

session_destroy();

// RECARGAR INDEX AL CERRAR SESIÓN
header("Location: index.php?logout=" . time());
exit;
?>