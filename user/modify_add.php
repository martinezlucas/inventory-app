<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }

    $id = $_GET['id'];
    $page = $_GET['page'];
    
    $connection = new Connection();
    $add_data = $connection->get_row_by_id('producto_agregado', $id);
    $user_name = $connection->get_user_name($add_data['id_usuario']);

    if(empty($add_data['modif_por'])) {
        $modified_by = "";
    } else {
        $modified_by = $connection->get_user_name($add_data['modif_por']);
    }

    $connection->close();

    /* Se verifica que los usuarios sin privilegios de administrador solo puedan modificar sus registros */
    if($_SESSION['user_rol'] != 1) {
        if($_SESSION['user_id'] != $add_data['id_usuario']) {
            header('location:../');
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contar producto</title>
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
                <?php if($page == "user_adds"): ?>
                    <a href="user_counts.php" class="navigation-option">Atrás</a>
                <?php else: ?>
                    <a href="codes_added.php" class="navigation-option">Atrás</a>
                <?php endif; ?>

                <a href="../user/menu.php" class="navigation-option">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Actualizar producto agregado</h1>
        <br>
        <form action="../server/update_add.php" id="sent-form" method="POST" class="form soft-border">
            <p>ID: <?php echo $add_data['id']; ?></p>
            <br>
            <p>Código: <?php echo $add_data['codigo']; ?></p>
            <br>
            <p>Contado por: <?php echo $user_name; ?></p>
            <br>
            <p>Fecha conteo: <?php echo $add_data['registrado']; ?></p>
            <br>    
            <p>Modificado por: <?php echo $modified_by; ?></p>
            <br>
            <p>Fecha modificación: <?php echo $add_data['modificado']; ?></p>
            <br>
            <input type="hidden" name="id-count" value="<?php echo $add_data['id']; ?>">
            <input type="hidden" name="user-id" value="<?php echo $add_data['id_usuario']; ?>">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <label for="code"><strong>Código:</strong></label>
            <input type="text" name="code" value="<?php echo $add_data['codigo']; ?>">
            <label for="description"><strong>Descripción:</strong></label>
            <textarea name="description" id="description" cols="30" rows="2" required><?php echo $add_data['descripcion']; ?></textarea>
            <input type="text" name="location" value="<?php echo $add_data['ubicacion']; ?>">
            <input type="number" name="quantity" placeholder="Cantidad" value="<?php echo $add_data['cantidad']; ?>" required>            
            <input type="submit" id="update" name="update" value="Actualizar">
        </form>        
    </main>
</body>
</html>