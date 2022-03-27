<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        $user_id = $_SESSION['user_id'];

        $connection = new Connection();
        $count_by_user = $connection->get_count_by_user($user_id);
        $user_name = $connection->get_user_name($user_id);
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
                <a href="menu.php" class="navigation-option">Atrás</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
               
       <h1 class="center-text">Conteo por usuario</h1>
       <h2 class="center-text">Usuario: <?php echo $user_name; ?></h2>

       <?php while($row = $count_by_user->fetch_assoc()): ?>
            <div class="soft-border card">
                <p>ID: <?php echo $row['id']; ?></p>
                <p>Código: <?php echo $row['codigo_producto']; ?></p>
                <p>Cantidad: <?php echo $row['cantidad']; ?></p>
                <p>registrado: <?php echo $row['registrado']; ?></p>
            </div>
       <?php 
            endwhile; 
            $count_by_user->free();
            $connection->close();
       ?>
    </main>
</body>
</html>