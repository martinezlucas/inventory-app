<?php

    require '../server/connection.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
    } else {

        $connection = new Connection();
        $last_code_counted = $connection->get_last_code_counted_by_user($_SESSION['user_id']);
        $last_add = $connection->get_last_add_by_user($_SESSION['user_id']);
        $location_data = $connection->get_location();

        if(empty($location_data)) {
            $location = "none";
        } else {
            $location = $location_data['codigo'];
        }

        if(empty($last_add)) {
            $add_code = "";
            $add_quantity = "";
        } else {
            $add_code = $last_add['codigo'];
            $add_quantity = $last_add['cantidad'];
        }

        if(empty($last_code_counted)) {
            $counted_code = "";
            $counted_quantity = "";
        } else {
            $counted_code = $last_code_counted['codigo_producto'];
            $counted_quantity = $last_code_counted['cantidad'];
        }

        if(isset($_POST['search'])) {             
            
            $code = trim($connection->get_connection()->real_escape_string($_POST['code']));            

            $error = null;
            
            if(empty($code)) {

                $error = "Se ha ingresado una cadena vacía";
                
            } else {

                $code_count = $connection->check_code($code);

                if($code_count == 0) {

                    $error = "No se encuentra el código: " . $code;
                    
                } else {
    
                    header('location:../user/code_founded.php?code=' . urlencode($code));
                }
            }
        }

        $connection->close();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar producto</title>

    <link rel="preload" href="../css/normalize.css" as="style">
    <link rel="preload" href="../css/styles.css" as="style">

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
        <h1 class="center-text">Buscar producto</h1>

        <form method="POST" class="form soft-border">
            <input type="text" name="code" id="code" placeholder="Código" required>            
            <input type="submit" name="search" value="Buscar">
        </form>

        <?php if(!empty($error)): ?>
            <br>
            <p class="center-text"><?php echo $error ?></p>
        <?php endif; ?>
    </main>
</body>
</html>