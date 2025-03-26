<?php
session_start();
if(empty($userID)){
    header('location: login.php');
}
?>