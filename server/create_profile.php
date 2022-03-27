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

    if(isset($_POST['create-user'])) {

        $connection = new Connection();

        $name = $connection->get_connection()->real_escape_string($_POST['name']);
        $user_name = $connection->get_connection()->real_escape_string($_POST['user-name']);
        $password = $connection->get_connection()->real_escape_string($_POST['password']);
        $role = $connection->get_connection()->real_escape_string($_POST['role']);

        if(empty($name) || empty($user_name) || empty($password) || empty($role)) {

            header('location:../admin/user_administration.php');

        } else {

            $user_created = $connection->create_user($name, $user_name, $password, $role);

            if($user_created == 0) {

                header('location:/logs/user_create_error.php');

            } else {

                header('location:../admin/user_administration.php');
            }
        }     
    }
?>