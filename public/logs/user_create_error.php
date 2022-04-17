<?php
    session_start();

    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
        }

    } else {

        header('location:../');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de creación o actualización</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">
        <nav>
            <a href="../user/menu.php" class="button bg-white center-text">Menú principal</a>
            <a href="../server/logout.php" class="button bg-white center-text">Cerrar sesión</a>
        </nav>
    </header>

    <h1 class="center-text">Se ha producido un error</h1>

    <p class="center-text">
        No es posible crear o actualizar al usuario.
    </p>
</body>
</html>