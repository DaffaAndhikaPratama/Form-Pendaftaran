<?php
session_start();

$host = "localhost";
$user = "root";
$password = "Daffa4nd1ka!"; // UBAH SESUAI PASSWORD DATABASE ANDA
$dbnm = "tugas_db";

$conn = mysqli_connect($host, $user, $password, $dbnm);

if (!$conn) {
    // Pesan error jika koneksi gagal
    die("<div style='padding: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Koneksi ke database gagal. Error: " . mysqli_connect_error() . "</div>"); 
}

// Fungsi untuk membersihkan input dari serangan XSS dan SQL Injection
function clean_input($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

?>