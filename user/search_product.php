<?php

    require '../server/connection.php';
    require '../server/validate.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
        
    } else {

        $connection = new Connection();
        $user_id = $_SESSION['user_id'];
        $last_code_counted = $connection->get_last_code_counted_by_user($user_id);
        $last_add = $connection->get_last_add_by_user($user_id);
        $location = $connection->get_location_by_user($user_id);

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

        if($_SERVER['REQUEST_METHOD'] == 'POST') {             
            
            $validate = new Validate();
            $code = htmlspecialchars_decode($validate->input($_POST['code']));            

            $error = null;
            $empty_string = false;
            
            if(empty($code)) {

                $error = "Se ha ingresado una cadena vacía";
                $empty_string = true;

            } else {

                $code_count = $connection->check_code($code);

                if($code_count == 0) {

                    $error = "No se encuentra el código: " . $code;
                    $subcode = substr($code, 0, intval(strlen($code) / 2));
                    $similar_codes = $connection->search_similar_codes("%" . $subcode . "%");
                    
                } else {
    
                    header('location:../user/count.php?code=' . urlencode($code) . "&page=search");
                    die();
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
    <header class="header-menu">
        <h1 class="cl-white no-margin">Buscar producto</h1>
        <div class="options">
            <a href="#" class="options-button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <nav class="navigation">
                <a href="../user/menu.php" class="navigation-option cl-black">Atrás</a>
                <a href="../server/logout.php" class="navigation-option cl-black">Cerrar sesión</a>
            </nav>
        </div>
    </header>

    <main>        
        
        <div class="soft-border info" id="code-added-div">
            <p>Último código agregado: <span id="code-added"><?php echo $add_code; ?></span></p>
            <p>Cantidad: <?php echo $add_quantity; ?></p>
        </div>

        <div class="soft-border info" id="code-counted-div">
            <p>Último código contado: <span id="code-counted"><?php echo $counted_code; ?></span></p>
            <p>Cantidad: <?php echo $counted_quantity; ?></p>
        </div>

        <div class="soft-border info" id="code-counted-div">
            <p>Ubicación seleccionada: <span><?php echo $location; ?></span></p>
            <br>
            <a href="locate.php" class="button border block cl-white bg-blue" style="width: 100%;">Establecer ubicación</a>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form soft-border">
            <input type="text" name="code" id="code" placeholder="Código" required>            
            <input type="submit" name="search" value="Buscar">
        </form>

        <?php if(!empty($error)): ?>
            <br>
            <p class="center-text"><?php echo $error ?></p>
            <br>
            <?php if(!$empty_string): ?>
                
                <a href="add_product.php?code=<?php echo $code; ?>" class="button block-button bg-green cl-white">Agregar al conteo</a>

                <?php if(!empty($similar_codes)): ?>
                    <br>
                    <h3 class="center-text">Códigos similares</h3>
                    
                    <?php while($row = $similar_codes->fetch_assoc()): ?>
                        <div class="soft-border info">
                            <p>Código: <?php echo $row['codigo']; ?></p>
                            <p>Marca: <?php echo $row['marca']; ?></p>
                            <p>Descripcion: <?php echo $row['descripcion']; ?></p>
                            <br>
                            <a href="count.php?code=<?php echo urlencode($row['codigo']); ?>&page=search" class="button block-button soft-border cl-white bg-blue">Contar</a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>                
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <script src="../js/validate.js"></script>
</body>
</html>