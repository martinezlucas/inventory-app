<?php

    require '../server/connection.php';
    require '../objects/pagination.php';

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
    $roles = $connection->get_table('rol');    
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
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="user_administration.php" class="navigation-option cl-black">Atrás</a>
                <a href="../user/menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Creación de usuarios</h1>
        <br>
        <form action="../server/create_profile.php" class="form soft-border" method="POST">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" placeholder="Nombre" required>
            <label for="user-name">Nombre de usuario</label>
            <input type="text" id="user-name" name="user-name" placeholder="Usuario" required>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <label for="role">Rol</label>
            <select name="role" id="role" name="role" required>
                <option disabled selected="selected">-- Elige un rol --</option>

                <?php while($row = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                <?php 
                    endwhile;
                    $roles->free();
                    $connection->close();
                ?>
            </select>
            <input type="submit" name="create-user" value="Crear usuario">
        </form>
    </main>
</body>
</html>