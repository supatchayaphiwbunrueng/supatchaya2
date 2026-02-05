<?php
$conn = new mysqli("localhost","root","","auth_system");
if($conn->connect_error){
    die("DB Error");
}
session_start();
?>
