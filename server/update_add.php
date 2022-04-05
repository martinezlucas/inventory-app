<?php

    require "connection.php";

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        if(isset($_POST['update'])) {

            $id_count = $_POST['id-count'];
            $code = $_POST['code'];
            $user_id = $_SESSION['user_id'];
            $id_user = $_POST['user-id'];
            $code = $_POST['code'];
            $page = $_POST['page'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            $location = strtoupper($_POST['location']);            

            if($_SESSION['user_rol'] != 1) {
                if($user_id != $id_user) {
                    header('location:../');
                    die();
                }
            }

            $connection = new Connection();
            
            $add_updated = $connection->update_add($user_id, $id_count, $code, $description, $quantity, $location);

            if($add_updated > 0) {

                switch($page) {
                    case "user_adds":
                        header('location:../user/user_adds.php');
                        die();
                        break;

                    case "codes_added":
                        header('location:../user/codes_added.php');
                        die();
                        break;
                    
                    default:
                        header('location:../logs/error_connection.html');
                        die();
                }
            }

        } else {

            header('location:../user/menu.php');
        }
    }
?>