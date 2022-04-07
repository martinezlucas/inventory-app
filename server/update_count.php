<?php

    require "connection.php";

    session_start();
    
    if(!isset($_SESSION['user_id'])) {

        header('location:../');

    } else {

        if(isset($_POST['update'])) {

            $id_count = $_POST['id-count'];
            $user_id = $_SESSION['user_id'];
            $code = $_POST['update-code'];
            $page = $_POST['page'];
            $quantity = $_POST['quantity'];
            $location = strtoupper($_POST['location']);

            $connection = new Connection();
            
            $code_updated = $connection->update_count($user_id, $id_count, $quantity, $location);

            if($code_updated > 0) {

                switch($page) {
                    case "differences":
                        header('location:../user/count_details.php?code=' . urlencode($code) . "&page=differences");
                        die();
                        break;

                    case "table":
                        header('location:../user/count_details.php?code=' . urlencode($code) . "&page=table");
                        die();
                        break;

                    case "user_counts":
                        header('location:../user/user_counts.php');
                        die();
                        break;

                    case "inventory":
                        header('location:../user/inventory_count.php');
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