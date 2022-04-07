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
    $users = $connection->get_table('persona');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de usuarios</title>
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
        <h1 class="center-text hidden-block">Administración de usuarios</h1>
        <br>
        <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>

        <table class="table hidden-table">           
           <tr>
               <th class="column-title">ID</th>
               <th class="column-title">Nombre</th>
               <th class="column-title">Usuario</th>
               <th class="column-title">Rol</th>
               <th class="column-title">Registrado</th>
               <th class="column-title">Actualizado</th>
           </tr>

           <?php                 
                while($row = $users->fetch_assoc()):
                    
                    $rol_description = $connection->get_rol_description($row['rol']);
           ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['usuario']; ?></td>
                <td><?php echo $rol_description; ?></td>
                <td><?php echo $row['registrado']; ?></td>
                <td><?php echo $row['actualizado']; ?></td>
                                
                <?php if($row['id'] != $_SESSION['user_id']): ?>
                    <td><a href="update_user.php?id=<?php echo $row['id']; ?>" class="button-table">Actualizar</a></td>
                <?php endif; ?>

                <?php if($row['rol'] != 1): ?>
                    <td><a href="delete_user.php?id=<?php echo $row['id']; ?>" class="button-table">Borrar</a></td>
                <?php endif; ?>    
            </tr>

            <?php 
                endwhile; 
                $users->free();
                $connection->close();
            ?>            
       </table>

       <a href="create_user.php" class="bg-blue cl-white center-button hidden-block">Nuevo usuario</a>
    </main>
</body>
</html>