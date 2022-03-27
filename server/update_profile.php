<?php

    require '../server/connection.php';

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
        }

    } else {

        header('location:../');
    }

    if(isset($_POST['update-user'])) {

        $connection = new Connection();

        $user_id = $connection->get_connection()->real_escape_string($_POST['id-user']);
        $name = $connection->get_connection()->real_escape_string($_POST['name']);
        $user_name = $connection->get_connection()->real_escape_string($_POST['user-name']);
        $password = $connection->get_connection()->real_escape_string($_POST['password']);
        $role = $connection->get_connection()->real_escape_string($_POST['role']);

        $user_updated = $connection->update_user(intval($user_id), $name, $user_name, intval($role));

        if($user_updated == 0) {

            header('location:../logs/user_create_error.html');

        } else {

            if(!empty($password)) {

                $password_reseted = $connection->update_password(intval($user_id), $password);

                if($password_reseted == 0) {
                    header('location:../logs/user_create_error.html');
                } else {
                    header('location:../admin/user_administration.php');        
                }
            }

            header('location:../admin/user_administration.php');
        }
    }
?>