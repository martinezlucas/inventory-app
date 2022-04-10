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
    $count_data = $connection->get_row_by_id('inventario', $id);
    $user_name = $connection->get_user_name($count_data['id_usuario']);

    if(empty($count_data['modif_por'])) {
        $modified_by = "";
    } else {
        $modified_by = $connection->get_user_name($count_data['modif_por']);
    }

    $connection->close();

    /* Se verifica que los usuarios sin privilegios de administrador solo puedan modificar sus registros */
    if($_SESSION['user_rol'] != 1) {
        if($_SESSION['user_id'] != $count_data['id_usuario']) {
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
    <header class="header-menu">
        <h1 class="cl-white no-margin">Actualizar conteo</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <?php if($page == "user_counts"): ?>
                    <a href="user_counts.php" class="navigation-option cl-black">Atrás</a>
                <?php else: ?>
                    <a href="count_details.php?code=<?php echo $count_data['codigo_producto']; ?>&page=<?php echo $page; ?>" class="navigation-option cl-black">Atrás</a>
                <?php endif; ?>

                <a href="../user/menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>                
        <br>
        <form action="../server/update_count.php" id="sent-form" method="POST" class="form soft-border">
            <p>ID: <?php echo $count_data['id']; ?></p>
            <br>
            <p>Código: <?php echo $count_data['codigo_producto']; ?></p>
            <br>
            <p>Contado por: <?php echo $user_name; ?></p>
            <br>
            <p>Fecha conteo: <?php echo $count_data['registrado']; ?></p>
            <br>    
            <p>Modificado por: <?php echo $modified_by; ?></p>
            <br>
            <p>Fecha modificación: <?php echo $count_data['modificado']; ?></p>
            <br>
            <input type="hidden" name="id-count" value="<?php echo $count_data['id']; ?>">            
            <input type="hidden" name="update-code" value="<?php echo $count_data['codigo_producto']; ?>">            
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <input type="text" name="location" value="<?php echo $count_data['ubicacion']; ?>">
            <input type="number" name="quantity" placeholder="Cantidad" value="<?php echo $count_data['cantidad']; ?>" required>            
            <input type="submit" id="update" name="update" value="Actualizar">
        </form>        
    </main>
</body>
</html>