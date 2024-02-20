<?php

// iniciar la sesion
session_start();

// destruir las variables de sesion

session_unset();

// destruir la sesion

session_destroy();

// redirigir a la pagina de login

header("Location: index.php");
exit(); // Finalizar el script


?>