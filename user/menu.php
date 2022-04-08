<?php

    require '../server/connection.php';

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
    <header class="header">        
        <nav>
            <a href="../server/logout.php" rel="noreferrer noopener" class="button btn-mn center-text bg-white">Cerrar sesión</a>
        </nav>
    </header>

    <main>        
        <h1 class="center-text">Menú principal</h1>
        <br>
        <div class="menu soft-border">
            <nav>                
                <?php if($_SESSION['user_rol'] == 1): ?>
                    <a href="codes_table.php" rel="noreferrer noopener" class="button center-text bg-blue cl-white">Códigos subidos a BD</a>
                    <a href="inventory_count.php" rel="noreferrer noopener" class="button center-text bg-blue cl-white">Conteo general</a>
                    <a href="differences.php" rel="noreferrer noopener" class="button center-text bg-blue cl-white">Diferencias del conteo</a>
                    <a href="codes_added.php" rel="noreferrer noopener" class="button center-text bg-blue cl-white">Códigos agregados general</a>
                    <a href="../admin/user_administration.php" rel="noreferrer noopener" class="button center-text bg-blue cl-white">Administrar usuarios</a>
                <?php endif; ?>
                <a href="user_counts.php" rel="noreferrer noopener" class="button center-text bg-green cl-white">Conteo por usuario</a>
                <a href="user_adds.php" rel="noreferrer noopener" class="button center-text bg-green cl-white">Códigos agregados por usuario</a>
                <a href="search.php" rel="noreferrer noopener" class="button center-text bg-green cl-white">Buscar productos por ubicación</a>
                <a href="search_product.php" rel="noreferrer noopener" class="button center-text bg-green cl-white">Contar producto</a>
            </nav>
        </div>
    </main>
</body>
</html>