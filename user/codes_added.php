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

    $user_id = $_SESSION['user_id'];
    $connection = new Connection();
    $codes_added = $connection->get_table('producto_agregado');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos agregados general</title>
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
                <a href="../server/download_codes_added.php" class="navigation-option">Descargar tabla</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
               
       <h1 class="center-text hidden-block">Códigos agregados por usuario</h1>
       <br>
       <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>

       <table class="table hidden-table">           
           <tr>
               <th class="column-title">ID</th>
               <th class="column-title">Código</th>
               <th class="column-title">Cantidad</th>
               <th class="column-title">Descripción</th>
               <th class="column-title">Ubicación</th>
               <th class="column-title">Contado por</th>
               <th class="column-title">Fecha conteo</th>
           </tr>

           <?php 
                while($row = $codes_added->fetch_assoc()): 
                    $user_name = $connection->get_user_name($row['id_usuario']);
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['codigo']; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td style="width: 20rem;"><?php echo $row['descripcion']; ?></td>
                <td style="width: 20rem;"><?php echo $row['ubicacion']; ?></td>
                <td><?php echo $user_name; ?></td>
                <td><?php echo $row['registrado']; ?></td>
            </tr>

            <?php 
                endwhile; 
                $codes_added->free();
                $connection->close();
            ?>
       </table> 
    </main>
</body>
</html>