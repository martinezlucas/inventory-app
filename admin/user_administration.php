<?php

    require '../server/connection.php';
    require '../server/validate.php';
    require '../objects/pagination.php';

    session_start();

    if (isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if ($_SESSION['user_rol'] != 1) {

            header('location:../');
        }
    } else {

        header('location:../');
    }

    $connection = new Connection();
    $users_count = $connection->get_users_count();

    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 10;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($users_count);
    $pagination->set_buttons_hidden(true);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $users_per_page = $connection->get_rows_per_page($index, $rows_per_page, "persona", "id");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $validate = new Validate();
        $user = htmlspecialchars_decode($validate->input($_POST['user']));

        $error = null;

        if (empty($user)) {

            $error = "Se ha ingresado una cadena vacía";
        } else {

            $user_count = $connection->find_user($user);

            if ($user_count == 0) {

                $error = "No se encuentra el usuario con nombre: " . $user;
            } else {

                $users_data = $connection->get_users_by_name($user);
            }
        }
    }
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
                <a href="../user/menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <h1 class="center-text hidden-block">Administración de usuarios</h1>
        <br>
        <p class="center-text hidden-message">Para visualizar la tabla utilice una computadora de escritorio o portatil</p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search hidden-flex">
            <input type="text" name="user" id="user" placeholder="Buscar usuario" required>
            <input type="submit" name="search" value="&#128269;">
        </form>
        <br>
        <table class="table hidden-table">
            <tr>
                <th class="column-title">ID</th>
                <th class="column-title">Nombre</th>
                <th class="column-title">Usuario</th>
                <th class="column-title">Rol</th>
                <th class="column-title">Registrado</th>
                <th class="column-title">Actualizado</th>
            </tr>

            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                <?php if (!empty($error)) : ?>
                    <br>
                    <p class="center-text"><?php echo $error ?></p>
                    <br>
                <?php else : ?>
                    <?php
                        while ($row = $users_data->fetch_assoc()) :
                        $rol_description = $connection->get_rol_description($row['rol']);
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $rol_description; ?></td>
                            <td><?php echo $row['registrado']; ?></td>
                            <td><?php echo $row['actualizado']; ?></td>

                            <?php if ($row['id'] != $_SESSION['user_id']) : ?>
                                <td><a href="update_user.php?id=<?php echo $row['id']; ?>" class="button-table">Actualizar</a></td>
                            <?php endif; ?>

                            <?php if ($row['rol'] != 1) : ?>
                                <td><a href="delete_user.php?id=<?php echo $row['id']; ?>" class="button-table">Borrar</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php
                        endwhile;
                        $users_data->free();                        
                    ?>
                <?php endif; ?>
            <?php else : ?>
                <?php
                    while ($row = $users_per_page->fetch_assoc()) :
                    $rol_description = $connection->get_rol_description($row['rol']);
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['usuario']; ?></td>
                        <td><?php echo $rol_description; ?></td>
                        <td><?php echo $row['registrado']; ?></td>
                        <td><?php echo $row['actualizado']; ?></td>

                        <?php if ($row['id'] != $_SESSION['user_id']) : ?>
                            <td><a href="update_user.php?id=<?php echo $row['id']; ?>" class="button-table">Actualizar</a></td>
                        <?php endif; ?>

                        <?php if ($row['rol'] != 1) : ?>
                            <td><a href="delete_user.php?id=<?php echo $row['id']; ?>" class="button-table">Borrar</a></td>
                        <?php endif; ?>
                    </tr>
                <?php
                    endwhile;
                    $users_per_page->free();                    
                ?>
            <?php endif; ?>            
            
        </table>
        <?php $connection->close(); ?>
        <br>
        <a href="create_user.php" class="bg-blue cl-white center-button hidden-block">Nuevo usuario</a>
        <?php $pagination->show_buttons(); ?>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <div class="hidden-flex">
                <a href="user_administration.php" rel="noreferrer noopener" class="button border cl-black">Reiniciar</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>