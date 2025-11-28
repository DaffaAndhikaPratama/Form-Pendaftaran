<?php
include 'db.php';
$message = ""; 

if (isset($_POST['register'])) {
    $username = clean_input($conn, $_POST['username']);
    $email = clean_input($conn, $_POST['email']);
    $password = clean_input($conn, $_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $message = "<div class='alert alert-danger' role='alert'>Username atau email sudah terdaftar.</div>";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?status=success");
            exit(); 
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f4f7fa; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }
        .register-container { 
            max-width: 400px; 
            padding: 40px; 
            border-radius: 12px; 
            background-color: #fff; 
            box-shadow: 0 10px 30px rgba(0,0,0,.08); 
        }
        .btn-success {
            background-color: #28a745; 
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4 text-success fw-bold">Buat Akun Baru</h2>
            <?php echo $message; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label text-muted">Username</label>
                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-muted">Email</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-muted">Password</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="register" class="btn btn-success btn-lg w-100 mt-3">Daftar</button>
            </form>
            
            <p class="mt-4 text-center text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>