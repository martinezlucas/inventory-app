<?php

    require '../server/connection.php';
    require '../server/validate.php';

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }

    if(!isset($_GET['page'])) {
        header('location:../');
        die();
    }

    $validate = new Validate();
    $page = $validate->input($_GET['page']);

    switch($page) {
        case 'user_counts':
            $back = 'user_counts.php';
            break;
        
        case 'user_adds':
            $back = 'user_adds.php';
            break;

        default:
            header('location:../');
            die();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código guardado</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-menu">
        <h1 class="cl-white no-margin">Conteo borrado</h1>
        <a href="../server/logout.php" class="button btn-mn block bg-white cl-black">Cerrar sesión</a>
    </header>

    <main>
        <br>        
        <nav class="menu soft-border">
            <a href="<?php echo $back; ?>" class="button center-text bg-blue cl-white">Atrás</a>
            <a href="menu.php" class="button center-text bg-blue cl-white">Menú principal</a>
        </nav>
    </main>
</body>
</html>