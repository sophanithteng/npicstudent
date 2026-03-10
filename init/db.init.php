<?php 
    $db_host = '127.0.0.1';
    $db_user = 'root';      
    $db_pwd  = '';        
    $db_name = 'npicstudent'; 
    $db_port = '3306';

    $db = new mysqli($db_host, $db_user, $db_pwd, $db_name, $db_port);

    if($db->connect_error){
        die("Connection failed: " . $db->connect_error);
    }
?>