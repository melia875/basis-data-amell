<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'siswa') { header("Location: ../login.php"); }

$nis_user = $_SESSION['user'];

if (isset($_POST['kirim'])) {
    $kategori = $_POST['id_kategori'];
    $lokasi = $_POST['lokasi'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO aspirasi (nis, id_kategori, lokasi, keterangan, status) 
              VALUES ('$nis_user', '$kategori', '$lokasi', '$keterangan', 'menunggu')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Aspirasi berhasil dikirim!'); window.location='riwayat.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kirim Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Siswa Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="form_aspirasi.php">Buat Aduan</a>
            <a class="nav-link" href="riwayat.php">Riwayat</a>
            <a class="nav-link text-danger" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Form Pengaduan Sarana</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kategori Sarana</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                while($rk = mysqli_fetch_array($kat)){
                                    echo "<option value='$rk[id_kategori]'>$rk[keterangan_kategori]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab Komputer 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail Kerusakan / Masukan</label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Jelaskan secara detail..." required></textarea>
                        </div>
                        <button type="submit" name="kirim" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Aspirasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>