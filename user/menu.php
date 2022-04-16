<?php

    require '../../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal</title>

    <link rel="preload" href="../css/normalize.css" as="style">
    <link rel="preload" href="../css/styles.css" as="style">

    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-menu">        
        <h1 class="cl-white no-margin">Menú principal</h1>
        <nav>
            <a href="../server/logout.php" rel="noreferrer noopener" class="button btn-mn block bg-white cl-black">Cerrar sesión</a>
        </nav>
    </header>

    <main>                
        <br>
        <div class="menu soft-border">
            <nav>                
                <?php if($_SESSION['user_rol'] == 1): ?>
                    <p class="center-text hidden-message">Utilice una computadora de escritorio o portatil para acceder a las opciones de administrador</p>
                    <br class="hidden-message">
                    <div class="hidden-block">
                        <a href="codes_table.php" rel="noreferrer noopener" class="button bg-blue cl-white">Tabla de artículos</a>
                        <a href="inventory_count.php" rel="noreferrer noopener" class="button bg-blue cl-white">Conteo general</a>
                        <a href="differences.php" rel="noreferrer noopener" class="button bg-blue cl-white">Diferencias del conteo</a>
                        <a href="codes_added.php" rel="noreferrer noopener" class="button bg-blue cl-white">Códigos agregados general</a>
                        <a href="../admin/user_administration.php" rel="noreferrer noopener" class="button bg-blue cl-white">Administrar usuarios</a>
                        <br>
                    </div>
                <?php endif; ?>
                <a href="user_counts.php" rel="noreferrer noopener" class="button bg-green cl-white">Conteo por usuario</a>
                <a href="user_adds.php" rel="noreferrer noopener" class="button bg-green cl-white">Códigos agregados por usuario</a>
                <a href="search_by_location.php" rel="noreferrer noopener" class="button bg-green cl-white">Productos por ubicación</a>
                <a href="search_by_count.php" rel="noreferrer noopener" class="button bg-green cl-white">Productos por conteo</a>
                <a href="search_product.php" rel="noreferrer noopener" class="button bg-green cl-white">Contar producto</a>
            </nav>
        </div>
        <br>
    </main>
</body>
</html>