<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['username'])) {
    header("location:../index.php");
    exit;
}


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

 
    $cek = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_array($cek);
     
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama']     = $data['nama_admin'];
        $_SESSION['status']   = "login";

        echo "<script>alert('Login Berhasil! Selamat Datang.'); window.location='../index.php';</script>";
    } else {
        echo "<script>alert('Username atau Password Salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Aplikasi Kasir</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            background: #90353D; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #90353D;
        }
        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; 
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: #90353D;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-box button:hover {
            background: #7a2b32;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Aplikasi Kasir</h2>
    <p>Silahkan Login Terlebih Dahulu</p>
    <br>
    
    <form method="post">
        <input type="text" name="username" placeholder="Username" required autocomplete="off">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">LOGIN</button>
    </form>
    
    <br>
    <small style="color:#888;">Gunakan akun: admin / admin</small>
</div>

</body>
</html>