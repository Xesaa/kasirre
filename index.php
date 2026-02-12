<?php
session_start();


if (isset($_SESSION['username'])) {

    header("location:modul/dashboard.php");
} else {

    header("location:auth/login.php");
}
exit;