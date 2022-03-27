<?php

    require "connection.php";

    session_start();
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_rol'])) {

        if($_SESSION['user_rol'] != 1) {

            header('location:../');
        }

    } else {

        if(isset($_GET['id_count']) && isset($_GET['page'])) {

            $id_count = $_GET['id_count'];
            $code = urlencode($_GET['code']);
            $page = $_GET['page'];

            $connection = new Connection();

            $count_deleted = $connection->delete_count(intval($id_count));

            if($count_deleted == 0) {

                header('location:../logs/error_connection.html');
            } else {

                if($page == "differences") {
                    header('location:../user/count_details.php?code=' . $code . "&page=differences");
                } else if($page == "table") {
                    header('location:../user/count_details.php?code=' . $code . "&page=table");
                } else {
                    header('location:../user/menu.php');
                }
            }

        } else {

            header('location:../');
        }
    }
?>