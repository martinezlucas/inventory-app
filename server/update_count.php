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
            $location = $_POST['location'];

            $connection = new Connection();
            
            $code_updated = $connection->update_count($user_id, $id_count, $quantity, $location);

            if($code_updated > 0) {
                if($page == "differences") {
                    header('location:../user/count_details.php?code=' . urlencode($code) . "&page=differences");
                } else if($page == "table") {
                    header('location:../user/count_details.php?code=' . urlencode($code) . "&page=table");
                } else {
                    header('location:../user/menu.php');
                }
            } else {
                header('location:../logs/error_connection.html');
            }

        } else {

            header('location:../user/menu.php');
        }
    }
?>