<?php

    require "connection.php";

    session_start();

    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }

    if(isset($_GET['id_count']) && isset($_GET['page'])) {

        $id_count = $_GET['id_count'];
        $code = urlencode($_GET['code']);
        $page = $_GET['page'];

        $connection = new Connection();

        if($_SESSION['user_rol'] != 1) {

            $count_by_user = $connection->check_count_by_user($id_count, $_SESSION['user_id']);                                
            
            if($count_by_user == 0) {
                
                header('location:../logs/error_connection.html');
                die();
            }
        }

        $count_deleted = $connection->delete_count(intval($id_count));

        if($count_deleted == 0) {

            header('location:../logs/error_connection.html');
            die();

        } else {

            switch($page){

                case "differences":
                    header('location:../user/count_details.php?code=' . $code . "&page=differences");
                    die();
                    break;
                
                case "table":
                    header('location:../user/count_details.php?code=' . $code . "&page=table");
                    die();

                case "user_counts":
                    header('location:../user/user_counts.php');
                    die();

                default:
                    header('location:../');                
                    die();
            }
        }

    } else {

        header('location:../');
        die();
    }
?>