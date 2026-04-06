<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Cek Admin
    $queryAdmin = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
    // Cek Siswa
    $querySiswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$user' AND password='$pass'");

    if (mysqli_num_rows($queryAdmin) > 0) {
        $_SESSION['role'] = 'admin';
        $_SESSION['user'] = $user;
        header("Location: admin/dashboard.php");
    } elseif (mysqli_num_rows($querySiswa) > 0) {
        $data = mysqli_fetch_assoc($querySiswa);
        $_SESSION['role'] = 'siswa';
        $_SESSION['user'] = $data['nis'];
        header("Location: siswa/form_aspirasi.php");
    } else {
        echo "<script>alert('Username/NIS atau Password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Pengaduan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-primary { background: #4e73df; border: none; border-radius: 10px; padding: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-school fa-3x text-primary mb-2"></i>
                    <h4>Pengaduan Sekolah SMK 1</h4>
                    <p class="text-muted">Silakan Login</p>
                </div>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username / NIS</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan ID Anda" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="******" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login Sekarang</button>
                    <div class="text-center mt-3">
    <p class="text-muted small">Belum punya akun? <a href="register.php" class="text-primary fw-bold">Daftar Sekarang</a></p>
    <a href="index.php" class="text-secondary small"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
</div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>