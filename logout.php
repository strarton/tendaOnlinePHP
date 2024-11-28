<?php
// Iniciar sesión y destruirla
session_start();
session_unset();
session_destroy();

// Redirigir a la página de login o inicio
header("Location: login.php");
exit;
?>
