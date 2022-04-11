<?php
    require '../server/connection.php';
    require '../server/validate.php';
    require '../objects/pagination.php';

    session_start();

    if (!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }

    $user_id = $_SESSION['user_id'];

    $connection = new Connection();
    $user_name = $connection->get_user_name($user_id);
    $user_codes_count = $connection->get_counts_by_user('producto_agregado', $user_id);

    /* Paginación */
    $pagination = new Pagination();
    $rows_per_page = 4;
    $pagination->set_rows_per_page($rows_per_page);
    $pagination->set_total_rows($user_codes_count);
    $pagination->set_pagination();
    $index = $pagination->get_index();

    $rows_per_user = $connection->get_paginated_table_by_user('producto_agregado', $user_id, $index, $rows_per_page);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $validate = new Validate();
        $code = htmlspecialchars_decode($validate->input($_POST['code']));

        $error = null;

        if (empty($code)) {

            $error = "Se ha ingresado una cadena vacía";

        } else {

            $code_count = $connection->check_code($code);

            if ($code_count == 0) {

                $error = "No se encuentra el código: " . $code;

            } else {

                $code_added_by_user = $connection->get_add_by_code_and_user($code, $user_id);
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
    <title>Códigos agregados por usuario</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header class="header-menu">
        <h1 class="cl-white no-margin">Códigos por usuario</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="menu.php" class="navigation-option cl-black">Atrás</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <br>            
        <h2 class="center-text">Usuario: <?php echo $user_name; ?></h2>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search flex-center">
            <input type="text" name="code" id="code" placeholder="Buscar código" required>
            <input type="submit" name="search" value="&#128269;">
        </form>

        <br>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <?php if (!empty($error)) : ?>
                <br>
                <p class="center-text"><?php echo $error ?></p>
                <br>
            <?php else : ?>
                <?php while ($row = $code_added_by_user->fetch_assoc()) : ?>
                    <div class="soft-border card">
                        <p>ID: <?php echo $row['id']; ?></p>
                        <p>Código: <?php echo $row['codigo']; ?></p>
                        <p>Cantidad: <?php echo $row['cantidad']; ?></p>
                        <p>Descripción: <?php echo $row['descripcion']; ?></p>
                        <p>Ubicación: <?php echo $row['ubicacion']; ?></p>
                        <p>Registrado: <?php echo $row['registrado']; ?></p>
                        <p>Modificado: <?php echo $row['modificado']; ?></p>
                        <br>
                        <div class="card-menu">
                            <a href="modify_add.php?id=<?php echo $row['id']; ?>&page=user_adds" class="button border cl-white bg-green">Modificar</a>
                            <button id="delete-button" class="delete-button" onclick="deleteAdd(<?php echo $row['id']; ?>, 'user_adds')">Eliminar</button>
                        </div>
                    </div>

                    <?php
                        endwhile;
                        $code_added_by_user->free();
                    ?>
            <?php endif; ?>
        
        <?php else : ?>

            <?php while ($row = $rows_per_user->fetch_assoc()) : ?>

                <div class="soft-border card">
                    <p>ID: <?php echo $row['id']; ?></p>
                    <p>Código: <?php echo $row['codigo']; ?></p>
                    <p>Cantidad: <?php echo $row['cantidad']; ?></p>
                    <p>Descripción: <?php echo $row['descripcion']; ?></p>
                    <p>Ubicación: <?php echo $row['ubicacion']; ?></p>
                    <p>Registrado: <?php echo $row['registrado']; ?></p>
                    <p>Modificado: <?php echo $row['modificado']; ?></p>
                    <br>
                    <div class="card-menu">
                        <a href="modify_add.php?id=<?php echo $row['id']; ?>&page=user_adds" class="button border cl-white bg-green">Modificar</a>
                        <button id="delete-button" class="delete-button" onclick="deleteAdd(<?php echo $row['id']; ?>, 'user_adds')">Eliminar</button>
                    </div>
                </div>
            <?php
                endwhile;
                $rows_per_user->free();
            ?>

        <?php endif; ?>
        
        <?php $connection->close(); ?>

        <?php $pagination->show_buttons(); ?>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <a href="user_counts.php" rel="noreferrer noopener" class="center-button soft-border">Reiniciar</a>
        <?php endif; ?>

    </main>
    <script src="../js/confirm.js"></script>
</body>

</html>