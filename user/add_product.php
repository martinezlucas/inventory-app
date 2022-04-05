<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
    } else {

        $connection = new Connection();

    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contar producto</title>
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
                <a href="../user/menu.php" class="navigation-option">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Agregar producto</h1>

        <br>

        <form action="../server/add_count.php" method="POST" class="form soft-border">
            <label for="code">Código</label>
            <input type="text" id="code" name="code" placeholder="Código" required>
            <label for="quantity">Cantidad</label>
            <input type="number" id="quantity" name="quantity" placeholder="Cantidad" required>
            <label for="description">Descripción</label>
            <textarea name="description" id="description" cols="30" rows="2" required></textarea>
            <label for="location">Ubicación</label>            
            <input type="text" name="location" id="location" required>
            <input type="submit" name="add-count" value="Guardar">

        </form>        
    </main>
</body>
</html>