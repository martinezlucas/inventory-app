<?php

    require '../server/connection.php';

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
            die();
        }

    } else {

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
    <title>Códigos</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="codes_table.php" class="navigation-option">Atrás</a>
                <a href="../user/menu.php" class="navigation-option">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Cargar códigos para nuevo conteo</h1>
        <h2 class="center-text">Advertencia</h2>
        <p class="center-text">
            Esta acción eliminará los registros existentes en la base de datos, 
            por lo que se recomienda descargarlos antes de realizar esta acción.
        </p>

        <br>

        <form action="../server/load_csv.php" method="POST" class="form soft-border" enctype="multipart/form-data">
            <p id="info">Archivo seleccionado: ninguno</p>
            <br>
            <label for="csv_file" class="load-button">Buscar archivo CSV</label>
            <input type="file" name="csv_file" id="csv_file" accept="text/csv" class="hidden-input">
            <input type="submit" name="upload-file" id="upload-file" value="Subir archivo">
        </form>
    </main>

    <script src="../js/check.js"></script>
</body>
</html>