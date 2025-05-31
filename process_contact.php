<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];
    
    // Di sini Anda bisa menambahkan kode untuk menyimpan pesan ke database
    // atau mengirim email ke admin
    
    // Redirect kembali ke halaman utama dengan pesan sukses
    header("Location: index.php?contact=success");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?> 