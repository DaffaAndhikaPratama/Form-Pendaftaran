<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$message = '';
$upload_dir = 'uploads/'; 

// --- PASTIKAN FOLDER UPLOADS ADA ---
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// --- LOGIKA UPLOAD FILE ---
if (isset($_POST['upload_file'])) {
    $max_size = 5 * 1024 * 1024; // 5MB
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
    
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
        $file = $_FILES['file_upload'];
        $file_name = clean_input($conn, basename($file['name']));
        $file_type = $file['type'];
        $file_size = $file['size'];
        $file_tmp_name = $file['tmp_name'];

        if ($file_size > $max_size) {
            $message = "<div class='alert alert-danger'>Ukuran file terlalu besar (Maks 5MB).</div>";
        } elseif (!in_array($file_type, $allowed_types)) {
            $message = "<div class='alert alert-danger'>Tipe file tidak diizinkan (Hanya JPG, PNG, PDF).</div>";
        } else {
            // Amankan nama file dengan menambahkan user_id dan timestamp
            $new_file_name = $user_id . '_' . time() . '_' . $file_name;
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination)) {
                // INSERT: Menyimpan user_id
                $sql = "INSERT INTO uploads (user_id, file_name, stored_name, file_size, file_type) 
                        VALUES ('$user_id', '$file_name', '$new_file_name', '$file_size', '$file_type')";
                if (mysqli_query($conn, $sql)) {
                    $message = "<div class='alert alert-success'>File <b>$file_name</b> berhasil diunggah!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Gagal menyimpan data ke database.</div>";
                    unlink($destination); 
                }
            } else {
                $message = "<div class='alert alert-danger'>Gagal memindahkan file yang diunggah.</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-warning'>Silakan pilih file untuk diunggah.</div>";
    }
}

// --- AMBIL DAFTAR FILE (SELECT dengan WHERE user_id) ---
$files_sql = "SELECT id, file_name, stored_name, file_size FROM uploads WHERE user_id='$user_id' ORDER BY created_at DESC";
$files_result = mysqli_query($conn, $files_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengelolaan File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #f4f7fa; 
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            border: none;
        }
        .card-header {
            font-weight: bold;
            border-radius: 12px 12px 0 0 !important;
        }
        .btn-logout {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-logout:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        .btn-info {
            background-color: #3498db;
            border-color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        
        <div class="alert alert-light border shadow-sm p-4 d-flex justify-content-between align-items-center mb-5" role="alert" style="border-left: 5px solid #007bff !important;">
            <div>
                <h2 class="mb-1 text-primary fw-bold">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h2>
                <p class="mb-0 text-muted">Kelola semua unggahan file Anda di sini.</p>
            </div>
            <div>
                <a href="logout.php" class="btn btn-sm text-white btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-cloud-arrow-up me-2"></i> Unggah File Baru
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <p class="text-muted small">Pilih File (Maks 5MB, JPG/PNG/PDF):</p>
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" id="file_upload" name="file_upload" required>
                        <button type="submit" name="upload_file" class="btn btn-success">
                            <i class="bi bi-upload"></i> Unggah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-files me-2"></i> Daftar File Unggahan Anda
            </div>
            <ul class="list-group list-group-flush">
                <?php if (mysqli_num_rows($files_result) > 0): ?>
                    <?php while($file = mysqli_fetch_assoc($files_result)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-break me-3"><?php echo htmlspecialchars($file['file_name']); ?></span>
                            <div>
                                <a href="download.php?file=<?php echo urlencode($file['stored_name']); ?>&mode=view" target="_blank" class="btn btn-info btn-sm me-2">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="download.php?file=<?php echo urlencode($file['stored_name']); ?>&mode=download" class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted py-4">Belum ada file yang diunggah.</li>
                <?php endif; ?>
            </ul>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>