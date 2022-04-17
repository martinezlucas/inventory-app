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

    $connection = new Connection();
    $user_data = $connection->get_row_by_id('persona', $_GET['id']);
    $roles = $connection->get_table('rol');
    $location = $connection->get_location_by_user($_GET['id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header">
        <h1 class="cl-white no-margin">Actualizar usuario</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../admin/user_administration.php" class="navigation-option cl-black">Atrás</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>                
        <br>
        <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>
        <form action="../server/update_profile.php" class="hidden-form soft-border" method="POST">
            <p>ID: <?php echo $user_data['id']; ?></p>
            <br>
            <input type="hidden" name="id-user" value="<?php echo $user_data['id']; ?>">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" value="<?php echo $user_data['nombre']; ?>" placeholder="Nombre" required>
            <label for="user-name">Nombre de usuario</label>
            <input type="text" id="user-name" name="user-name" value="<?php echo $user_data['usuario']; ?>" placeholder="Usuario" required>
            <label for="password">Reestablecer contraseña</label>
            <input type="password" id="password" name="password" placeholder="Dejar en blanco si no se actualiza">
            <label for="location">Reestablecer ubicacion</label>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>">
            <label for="role">Rol</label>
            <select name="role" id="role" name="role">
                <option value="" disabled required>Elige un rol</option>

                <?php while($row = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"

                    <?php if($row['id'] == $user_data['rol']): ?>
                        selected
                    <?php endif; ?>

                    ><?php echo $row['descripcion']; ?></option>
                <?php 
                    endwhile;
                    $roles->free();
                    $connection->close();
                ?>
            </select>
            <input type="submit" name="update-user" value="Actualizar usuario">
        </form>
    </main>
</body>
</html>