<?php

    require '../server/connection.php';
    require '../server/validate.php';

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
            die();
        }

    } else {

        header('location:../');
        die();
    }

    if(isset($_POST['create-user'])) {

        $connection = new Connection();
        $validate = new Validate();

        $name = $validate->input($_POST['name']);
        $user_name = $validate->input($_POST['user-name']);
        $password = $validate->input($_POST['password']);
        $role = $validate->input($_POST['role']);

        if(empty($name) || empty($user_name) || empty($password) || empty($role)) {

            header('location:../admin/user_administration.php');
            die();

        } else {

            $user_created = $connection->create_user($name, $user_name, $password, $role);
            $user_id = $connection->get_user_id($user_name, $password);
            $location = $connection->set_location('Sin establecer', $user_id);
            $connection->close();

            if($user_created == 0) {

                header('location:/logs/user_create_error.php');
                die();

            } else {

                if($location == 0) {

                    header('location:/logs/user_create_error.php');
                    die();

                } else {

                    header('location:../admin/user_administration.php');
                    die();
                }                
            }
        }     
    }
?>