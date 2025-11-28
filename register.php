<?php
include 'db.php';
$massage = "";

if (isset($_POST['register'])) {
    $username = clean_input($conn, $_POST['username']);
    $email = clean_input($conn, $_POST['email']);
    $password = clean_input($conn, $_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $massage = "<div class='alert alert-danger' role='alert'>Username atau email sudah terdaftar.</div>";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        // ... di dalam logika POST['register']
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?status=success");
            exit(); // Penting untuk menghentikan eksekusi script selanjutnya
        } else {
            $massage = "<div class='alert alert-danger' role='alert'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4">Daftar Akun Baru</h2>
            <?php echo $massage; 
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo "<div class='alert alert-success text-center'>Pendaftaran berhasil! Silakan masuk dengan akun Anda.</div>";
            }
            ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label"></label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
    <script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    