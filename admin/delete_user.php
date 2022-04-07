<?php

    require '../server/connection.php';

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
        }

    } else {

        header('location:../');
    }

    $connection = new Connection();
    $user_data = $connection->get_row_by_id('persona', intval($_GET['id']));
    $user_deleted = $connection->delete_user(intval($_GET['id']));
    $connection->close();
    
    if($user_deleted == 0) {

        $title = "No es posible borrar al usuario: " . $user_data['usuario'];
        $message = "Al parecer el usuario que intenta borrar ha realizado acciones " . 
                    "dentro de la base de datos por lo que no es posible borrar su perfil.";
    } else {

        $title = "Se borró correctamente al usuario: " . $user_data['usuario'];
        $message = "Perfil borrado con éxito.";
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar usuario</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">
        <nav>
            <a href="user_administration.php" class="button bg-white center-text">Atrás</a>
            <a href="../server/logout.php" class="button bg-white center-text">Cerrar sesión</a>
        </nav>
    </header>

    <main>
        <?php echo $user_deleted; ?>        
        <h1 class="center-text"><?php echo $title; ?></h1>
        <p class="center-text"><?php echo $message; ?></p>
    </main>
</body>
</html>