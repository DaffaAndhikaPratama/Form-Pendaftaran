<?php
session_start();

$host = "localhost";
$user = "root";
$password = "Daffa4nd1ka!";
$dbnm = "tugas_db";

$conn = mysqli_connect($host, $user, $password, $dbnm);

if (!$conn) {
    die("div class='alert alert-danger' role='alert'>Koneksi ke database gagal: " . mysqli_connect_error() . "</div>");
}

function clean_input($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

?>