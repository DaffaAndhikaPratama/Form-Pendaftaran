<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

$sql = "SELECT username, email, created_at FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
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
                <h1 class="mb-4 text-primary fw-bold"><i class="bi bi-person-circle me-2"></i> Profil Pengguna</h1>

                <nav class="mb-4">
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="editprofile.php" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i> Edit Profil</a>
                </nav>

                <?php echo $message; ?>

                <div class="card p-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Username</div>
                            <div class="col-sm-8 fw-bold"><?php echo htmlspecialchars($user['username']); ?></div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Email</div>
                            <div class="col-sm-8"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4 text-muted">Terdaftar Sejak</div>
                            <div class="col-sm-8"><?php echo date("d F Y, H:i", strtotime($user['created_at'])); ?></div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="logout.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>