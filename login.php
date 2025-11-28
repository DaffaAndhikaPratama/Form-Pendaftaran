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
        
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
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
    <title>Login Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f4f7fa; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }
        .login-container { 
            max-width: 400px; 
            padding: 40px; 
            border-radius: 12px; 
            background-color: #fff; 
            box-shadow: 0 10px 30px rgba(0,0,0,.08); 
        }
        .btn-primary {
            background-color: #007bff; 
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4 text-primary fw-bold">Masuk ke Sistem</h2>
            <?php 
            echo $message; 
            
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo "<div class='alert alert-success text-center'>Pendaftaran berhasil! Silakan masuk dengan akun Anda.</div>";
            }
            ?>
            
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username_or_email" class="form-label text-muted">Username atau Email</label>
                    <input type="text" class="form-control form-control-lg" id="username_or_email" name="username_or_email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-muted">Password</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-lg w-100 mt-3">Masuk</button>
            </form>
            <p class="mt-4 text-center text-muted">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar sekarang</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>