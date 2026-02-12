<?php
// T:\laragon\www\kasirre\ceklogin.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("location:auth/login.php");
    exit;
}
?>