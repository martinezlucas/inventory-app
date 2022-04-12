<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        $code = $_GET['code'];
        $connection = new Connection();
        $code_data = $connection->get_code_data($code);
        $sum_of_code = $connection->get_sum_of_code($code);
        $count_by_code = $connection->get_count_by_code($code);
        $connection->close();
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
        <h1 class="cl-white no-margin">Detalle del producto</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../user/search_by_location.php" class="navigation-option cl-black">Atrás</a>
                <a href="../user/menu.php" class="navigation-option cl-black">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>                
        <br>
        <div class="form soft-border">
            <p>Línea: <?php echo $code_data['linea']; ?></p>
            <br>
            <p>Código: <?php echo $code_data['codigo']; ?></p>
            <br>
            <p>Marca: <?php echo $code_data['marca']; ?></p>
            <br>
            <p>Descripción: <?php echo $code_data['descripcion']; ?></p>
            <br>    
            <p>Unidades contadas: <?php echo $sum_of_code; ?></p>

            <?php if(mysqli_num_rows($count_by_code) != 0): ?>
                <br>
                <h3 class="center-text">Ubicaciones</h3>
                <br>
                <?php while($row = $count_by_code->fetch_assoc()): ?>
                    <p><?php echo $row['ubicacion'] . " cantidad: " . $row['cantidad']; ?></p>
                <?php endwhile; ?>
                <?php $count_by_code->free(); ?>    
            <?php endif; ?>
        </div>
    </main>
</body>
</html>