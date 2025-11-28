<?php
include 'db.php'; // Memuat db.php yang berisi session_start()

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$upload_message = '';
$target_dir = "uploads/";

// --- 1. LOGIKA UNGGAH FILE ---
if (isset($_POST['upload_file'])) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file benar-benar diunggah
    if ($_FILES["fileToUpload"]["error"] != UPLOAD_ERR_OK) {
        $upload_message = "<div class='alert alert-danger'>Terjadi error saat mengunggah. Error code: " . $_FILES["fileToUpload"]["error"] . "</div>";
        $uploadOk = 0;
    }

    // Cek jika file sudah ada
    if (file_exists($target_file)) {
        $upload_message = "<div class='alert alert-warning'>Maaf, file sudah ada.</div>";
        $uploadOk = 0;
    }

    // Batasi ukuran file (misalnya 5MB = 5000000 bytes)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $upload_message = "<div class='alert alert-warning'>Maaf, ukuran file terlalu besar (maksimal 5MB).</div>";
        $uploadOk = 0;
    }

    // Batasi tipe file (Contoh: hanya mengizinkan JPG, JPEG, PNG, PDF)
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf" ) {
        $upload_message = "<div class='alert alert-warning'>Maaf, hanya file JPG, JPEG, PNG & PDF yang diizinkan.</div>";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk bernilai 0
    if ($uploadOk == 0) {
        // Pesan error sudah diatur di atas
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $upload_message = "<div class='alert alert-success'>File **". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). "** berhasil diunggah.</div>";
        } else {
            $upload_message = "<div class='alert alert-danger'>Maaf, terjadi kesalahan saat mengunggah file. Pastikan folder `uploads/` dapat ditulisi.</div>";
        }
    }
}
// --- AKHIR LOGIKA UNGGAH FILE ---

// --- 2. LOGIKA TAMPIL FILE ---
// Fungsi untuk mendapatkan daftar file
$files = array_diff(scandir($target_dir), array('.', '..'));
// --- AKHIR LOGIKA TAMPIL FILE ---
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        
        <div class="alert alert-info">
            <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>Anda berhasil login ke dashboard.</p>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
        
        <hr>

        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h4>📥 Unggah File Baru</h4>
            </div>
            <div class="card-body">
                <?php echo $upload_message; // Menampilkan pesan hasil upload ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileToUpload" class="form-label">Pilih File (Maks 5MB, JPG/PNG/PDF):</label>
                        <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" required>
                    </div>
                    <button type="submit" name="upload_file" class="btn btn-success">Unggah File</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h4>📄 Daftar File Unggahan</h4>
            </div>
            <div class="card-body">
                <?php if (count($files) > 0): ?>
                    <ul class="list-group">
                        <?php foreach($files as $file): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($file); ?>
                                <a href="<?php echo $target_dir . htmlspecialchars($file); ?>" target="_blank" class="btn btn-sm btn-info">Lihat/Download</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-light text-center">Belum ada file yang diunggah.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>