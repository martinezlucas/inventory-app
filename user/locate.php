<?php

    require '../server/connection.php';
    require '../server/validate.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        $current_location = "error";

        if(isset($_GET['current_location'])) {
            $validate = new Validate();
            $current_location = $validate->input($_GET['current_location']);
        }
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
                <a href="../user/search_product.php" class="navigation-option">Atrás</a>
                <a href="../server/logout.php" class="navigation-option">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        <h1 class="center-text">Establecer ubicación</h1>

        <form action="../server/set_location.php" method="POST" class="form soft-border">
            <input type="text" name="code" id="code" placeholder="Código de ubicación" value="<?php echo $current_location; ?>" required>            
            <input type="submit" name="locate" value="Establecer">
        </form>
    </main>
</body>
</html>