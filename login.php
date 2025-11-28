<?php
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = '';

if (isset($_POST['login'])) {
    $username_or_email = clean_input($conn, $_POST['username_or_email']);
    $password_input    = clean_input($conn, $_POST['password']);
    
    
    $sql = "SELECT id, username, password FROM users WHERE username='$username_or_email' OR email='$username_or_email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password dengan hash yang tersimpan
        if (password_verify($password_input, $user['password'])) {
            // Login berhasil, buat sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Arahkan ke halaman dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Username/Email atau Password salah!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Username/Email atau Password salah!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 30px; border: 1px solid #ccc; border-radius: 10px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Login Akun</h2>
            <?php echo $message; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username_or_email" class="form-label">Username atau Email</label>
                    <input type="text" class="form-control" id="username_or_email" name="username_or_email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-success w-100">Masuk</button>
            </form>
            <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>