<?php

    require '../server/connection.php';
    require '../server/validate.php';

    $error_name = "";
    $error_password = "";
    $error_message = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $validate = new Validate();

        $user = $validate->input($_POST['user']);        
        $password = $validate->input($_POST['password']);

        if(empty($user)) {
            $error_name = "El nombre de usuario es requerido";
        }

        if(empty($password)) {
            $error_password = "La contraseña es requerida";
        }

        if(!empty($user) && !empty($password)) {            
            
            $connection = new Connection();
            $user_id = $connection->check_user($user, $password);
            
            if($user_id != 0) {

                $user_data = $connection->get_row_by_id('persona', $user_id);
                $connection->close();

                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_rol'] = $user_data['rol'];
                
                header('location:user/menu.php');
                die();

            } else {

                $error_message = 'Usuario o contraseña incorrectos';
            }

            $connection->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>

    <link rel="preload" href="css/normalize.css" as="style">
    <link rel="preload" href="css/styles.css" as="style">

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1 class="center-text">Asistente de inventario</h1>
    </header>

    <main>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form soft-border">            
            <h2 class="center-text">Inicio de sesión</h2>

            <input type="text" name="user" placeholder="Usuario" required>
            <?php if(!empty($error_name)): ?>
                <p style="margin-top: -0.8rem"><?php echo $error_name; ?></p>
                <br>
            <?php endif; ?>

            <input type="password" name="password" placeholder="Contraseña" required>
            <?php if(!empty($error_password)): ?>
                <p style="margin-top: -0.8rem"><?php echo $error_password; ?></p>
                <br>
            <?php endif; ?>

            <input type="submit" name="send" value="Iniciar sesión">
        </form>

        <?php if(!empty($error_message)): ?>
            <br>
            <p class="center-text"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </main>
</body>
</html>