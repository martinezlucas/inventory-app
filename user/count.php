<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
    } else {

        $code = $_GET['code'];
        $page = $_GET['page'];
        $location = $_GET['location'];
        $connection = new Connection();
        $code_data = $connection->get_code_data($code);
        $sum_of_code = $connection->get_sum_of_code($code);
        $connection->close();    
        
        if(empty($sum_of_code)) {
            $sum_of_code = 0;
        }

        if($page == "search") {
            $back = "search_product.php";
        } else {
            $back = "differences.php";
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
                <a href="<?php echo $back; ?>" class="navigation-option">Atrás</a>
                <a href="../user/menu.php" class="navigation-option">Menú principal</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Contar producto</h1>
        <form action="../server/save_count.php" id="sent-form" method="POST" class="form soft-border">
            <p>Línea: <?php echo $code_data['linea']; ?></p>
            <br>
            <p>Código: <?php echo $code_data['codigo']; ?></p>
            <br>
            <p>Marca: <?php echo $code_data['marca']; ?></p>
            <br>
            <p>Descripción: <?php echo $code_data['descripcion']; ?></p>
            <br>    
            <p>Unidades contadas: <?php echo $sum_of_code; ?></p>
            <br>
            <p>Ubicación seleccionada: <?php echo $location; ?></p>
            <br>
            <?php if($sum_of_code > 0): ?>
                <a href="count_details.php?code=<?php echo urlencode($code_data['codigo']); ?>&page=count" class="button nest-button" style="display: block;">Detalles del conteo</a>
            <br>
            <?php endif; ?>
            <input type="hidden" name="send-code" value="<?php echo $code_data['codigo']; ?>">            
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <input type="hidden" name="location" value="<?php echo $location; ?>">
            <input type="number" name="quantity" placeholder="Cantidad" required>            
            <input type="submit" id="count" name="count" value="Guardar">
        </form>        
    </main>
</body>
</html>