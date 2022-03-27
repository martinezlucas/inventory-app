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
    <header class="header">
        <a href="../server/logout.php" class="button bg-white">Cerrar sesión</a>
    </header>

    <main>        
        <h1 class="center-text">Error</h1>
        <p>
            Se ha producido un error al realizar la opreación seleccionada.
        </p>
    </main>
</body>
</html>