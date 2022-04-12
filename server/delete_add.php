<?php

    require "connection.php";

    session_start();

    if(!isset($_SESSION['user_id'])) {

        header('location:../');
        die();
    }

    if(isset($_GET['id_add']) && isset($_GET['page'])) {

        $id_add = $_GET['id_add'];
        $page = $_GET['page'];

        $connection = new Connection();

        if($_SESSION['user_rol'] != 1) {

            $add_by_user = $connection->check_add_by_user($id_add, $_SESSION['user_id']);                                
            
            if($add_by_user == 0) {
                
                header('location:../logs/error_connection.html');
                die();                
            }
        }

        $add_deleted = $connection->delete_add(intval($id_add));

        $connection->close();

        if($add_deleted == 0) {

            header('location:../logs/error_connection.html');
            die();            

        } else {

            switch($page){

                case "user_adds":
                    header('location:../user/code_deleted.php?page=user_adds');
                    die();
                    break;
                
                case "codes_added":
                    header('location:../user/codes_added.php');
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