<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// --- FASE 1: AMBIL DATA PROFIL SAAT INI ---
$sql_fetch = "SELECT username, email, password FROM users WHERE id='$user_id'";
$result_fetch = mysqli_query($conn, $sql_fetch);

if (mysqli_num_rows($result_fetch) === 0) {
    die("Data pengguna tidak ditemukan.");
}

$user = mysqli_fetch_assoc($result_fetch);
$current_username = $user['username'];
$current_email = $user['email'];
$hashed_password = $user['password'];


// --- FASE 2: PROSES PEMBARUAN ---
if (isset($_POST['update_profile'])) {
    $new_username = clean_input($conn, $_POST['username']);
    $new_email = clean_input($conn, $_POST['email']);
    $password_confirm = clean_input($conn, $_POST['password_confirm']);
    
    if (empty($new_username) || empty($new_email) || empty($password_confirm)) {
        $message = "<div class='alert alert-warning'>Semua kolom harus diisi.</div>";
    } else {
        // Cek apakah password konfirmasi benar
        if (!password_verify($password_confirm, $hashed_password)) {
            $message = "<div class='alert alert-danger'>Konfirmasi password salah. Pembaruan dibatalkan.</div>";
        } else {
            // Cek duplikasi username/email baru (kecuali milik diri sendiri)
            $sql_check_duplicate = "SELECT id FROM users WHERE (username='$new_username' OR email='$new_email') AND id!='$user_id'";
            $result_check_duplicate = mysqli_query($conn, $sql_check_duplicate);

            if (mysqli_num_rows($result_check_duplicate) > 0) {
                $message = "<div class='alert alert-warning'>Username atau Email baru sudah digunakan oleh pengguna lain.</div>";
            } else {
                // Lakukan pembaruan
                $sql_update = "UPDATE users SET username='$new_username', email='$new_email' WHERE id='$user_id'";
                
                if (mysqli_query($conn, $sql_update)) {
                    $_SESSION['username'] = $new_username; 
                    
                    // Perbarui variabel lokal untuk tampilan
                    $current_username = $new_username;
                    $current_email = $new_email;
                    
                    $message = "<div class='alert alert-success'>Profil berhasil diperbarui!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Gagal memperbarui profil: " . mysqli_error($conn) . "</div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7fa; }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            border: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4 text-primary fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Profil</h1>

                <nav class="mb-4">
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="profile.php" class="btn btn-sm btn-primary"><i class="bi bi-person-circle"></i> Lihat Profil</a>
                </nav>

                <?php echo $message; ?>

                <div class="card p-4">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username Baru</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($current_username); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Baru</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" required>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label text-danger fw-bold">Konfirmasi Password Anda</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Masukkan Password Saat Ini untuk Konfirmasi" required>
                                <small class="form-text text-muted">Konfirmasi diperlukan untuk menyimpan perubahan.</small>
                            </div>
                            
                            <button type="submit" name="update_profile" class="btn btn-success mt-3"><i class="bi bi-save"></i> Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>