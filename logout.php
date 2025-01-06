<?php
    //------ FIRST METHOD ----------
    // session_start();
    // if(isset($_SESSION['user'])){
    //     unset($_SESSION['user']);
    //     unset($_SESSION['type']);
    //     unset($_SESSION['id']);
    //     unset($_SESSION['pic']);
    // }
    // header("location:login.php");

    // //------ SECOND METHOD ----------
    session_start();
    if(isset($_SESSION['user'])){
        session_destroy();        
    }
    header("location:login.php");
?>