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
    $user_data = $connection->get_user_data($_GET['id']);
    $roles = $connection->get_roles();
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
        <nav>
            <a href="../user/menu.php" class="button bg-white">Menú principal</a>
            <a href="../server/logout.php" class="button bg-white">Cerrar sesión</a>
        </nav>
    </header>

    <main>        
        <h1 class="center-text">Creación de usuarios</h1>

        <form action="../server/update_profile.php" class="form soft-border" method="POST">
            <p>ID: <?php echo $user_data['id']; ?></p>
            <br>
            <input type="hidden" name="id-user" value="<?php echo $user_data['id']; ?>">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" value="<?php echo $user_data['nombre']; ?>" placeholder="Nombre" required>
            <label for="user-name">Nombre de usuario</label>
            <input type="text" id="user-name" name="user-name" value="<?php echo $user_data['usuario']; ?>" placeholder="Usuario" required>
            <label for="password">Resetear contraseña</label>
            <input type="password" id="password" name="password" placeholder="Dejar en blanco si no se actualiza">
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