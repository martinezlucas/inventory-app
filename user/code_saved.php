<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código guardado</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-menu">
        <h1 class="cl-white no-margin">Conteo exitoso</h1>
        <a href="../server/logout.php" class="button bg-white">Cerrar sesión</a>
    </header>

    <main>
        <br>        
        <nav class="menu soft-border">
            <a href="search_product.php" class="button center-text bg-blue cl-white">Nuevo Conteo</a>
            <a href="menu.php" class="button center-text bg-blue cl-white">Menú principal</a>
        </nav>
    </main>
</body>
</html>