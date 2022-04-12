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
    <title>Error</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-menu">
        <h1 class="cl-white no-margin">Error</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../user/menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>        
    </header>

    <main>        
        <br>
        <p class="center-text">
            Se ha producido un error al realizar la opreación seleccionada.
        </p>
    </main>
</body>
</html>