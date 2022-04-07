<?php

    require 'connection.php';
    require 'validate.php';

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
        $validate = new Validate();

        $user_id = $validate->input($_POST['id-user']);
        $name = $validate->input($_POST['name']);
        $user_name = $validate->input($_POST['user-name']);
        $password = $validate->input($_POST['password']);        
        $role = $validate->input($_POST['role']);
        $location = $validate->input($_POST['location']);

        $user_updated = $connection->update_user(intval($user_id), $name, $user_name, intval($role));
        $location_updated = $connection->update_location($location, $user_id);

        if($user_updated == 0) {

            $connection->close();
            header('location:../logs/user_create_error.html');
            die();

        } else {

            if(!empty($password)) {

                $password_reseted = $connection->update_password(intval($user_id), $password);

                if($password_reseted == 0) {

                    $connection->close();
                    header('location:../logs/user_create_error.html');
                    die();
                }
            }

            $connection->close();

            if($location_updated == 0) {
                header('location:../logs/user_create_error.html');
                die();
            }

            header('location:../admin/user_administration.php');
        }
    }
?>