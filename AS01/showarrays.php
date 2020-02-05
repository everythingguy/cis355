<?php
    session_start();

    print_r($GLOBALS);
    echo "<br><br>";
    
    print_r($_SERVER);
    echo "<br><br>";
    
    print_r($_REQUEST);
    echo "<br><br>";
    
    print_r($_POST);
    echo "<br><br>";
    
    print_r($_GET);
    echo "<br><br>";
    
    print_r($_FILES);
    echo "<br><br>";
    
    print_r($_ENV);
    echo "<br><br>";
    
    print_r($_COOKIE);
    echo "<br><br>";
    
    print_r($_SESSION);
    echo "<br><br>";
?>