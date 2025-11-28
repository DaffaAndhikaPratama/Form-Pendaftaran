<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_dir = 'uploads/';
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'view'; // Default: view

if (isset($_GET['file']) && !empty($_GET['file'])) {
    $stored_name = clean_input($conn, $_GET['file']);
    $filepath = $upload_dir . $stored_name;

    // Verifikasi Kepemilikan
    $sql = "SELECT file_name, file_type, file_size FROM uploads WHERE stored_name = '$stored_name' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $file_info = mysqli_fetch_assoc($result);
        $file_type = $file_info['file_type'];
        $file_name = $file_info['file_name'];
        
        if (file_exists($filepath)) {
            
            $disposition = '';

            if ($mode === 'download') {
                $disposition = 'attachment'; // Paksa download
            } elseif ($mode === 'view') {
                // Tampilkan inline untuk gambar dan PDF
                if (strpos($file_type, 'image/') !== false || $file_type === 'application/pdf') {
                    $disposition = 'inline'; 
                } else {
                    $disposition = 'attachment'; // Default: download jika bukan gambar/pdf
                }
            }

            // Atur header
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file_type);
            header('Content-Disposition: ' . $disposition . '; filename="' . $file_name . '"');
            header('Content-Length: ' . filesize($filepath));
            header('Cache-Control: public, must-revalidate, max-age=0');
            header('Pragma: public');
            
            readfile($filepath);
            exit;
            
        } else {
            die("Error: File fisik tidak ditemukan di server.");
        }
    } else {
        die("Error: File tidak ditemukan atau Anda tidak memiliki akses.");
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>