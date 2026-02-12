<?php
session_start();
include __DIR__ . '/../config/koneksi.php';
include __DIR__ . '/../ceklogin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Kasirre</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <strong>Aplikasi Kasirre</strong>
        <a href="dashboard.php">Dashboard</a>
        <a href="produk.php">Produk</a>
        <a href="pelanggan.php">Pelanggan</a>
        <a href="transaksi.php">Transaksi</a>
    </div>
    <div class="nav-right">
        <span>Halo, Admin</span>
        <a class="btn-logout" href="../auth/logout.php">Logout</a>
    </div>
</nav>

<div class="container">
