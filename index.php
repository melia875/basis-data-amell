<?php
include 'config/koneksi.php';

// Hitung statistik untuk dashboard depan
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='selesai'"));
$proses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='proses'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Aspirasi Sarana Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .hero-section {
            background: linear-gradient(rgba(0, 123, 255, 0.8), rgba(0, 123, 255, 0.8)), url('https://images.unsplash.com/photo-1523050853064-80041f00965f?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-10px); }
        .navbar { background-color: rgba(255,255,255,0.95) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn-login { border-radius: 30px; padding: 10px 30px; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#"><i class="fas fa-school me-2"></i>E-PENGADUAN</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link px-3" href="#statistik">Statistik</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#data">Data Pengaduan</a></li>
                <li class="nav-item"><a class="btn btn-primary btn-login ms-lg-3" href="login.php">Login Siswa / Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Suaramu, Perubahan Bagi Sekolah</h1>
        <p class="lead">Laporkan kerusakan sarana dan prasarana sekolah dengan cepat, transparan, dan akuntabel.</p>
        <a href="login.php" class="btn btn-light btn-lg mt-3 fw-bold text-primary px-5 shadow">Kirim Pengaduan Sekarang</a>
    </div>
</header>

<section id="statistik" class="container" style="margin-top: -50px;">
    <div class="row g-4 justify-content-center">
        <div class="col-md-3">
            <div class="card stat-card text-center p-4 shadow">
                <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold"><?= $total ?></h2>
                <p class="text-muted mb-0">Total Laporan</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center p-4 shadow border-bottom border-info">
                <i class="fas fa-spinner fa-3x text-info mb-3"></i>
                <h2 class="fw-bold"><?= $proses ?></h2>
                <p class="text-muted mb-0">Dalam Proses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center p-4 shadow border-bottom border-success">
                <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                <h2 class="fw-bold"><?= $selesai ?></h2>
                <p class="text-muted mb-0">Tuntas Diperbaiki</p>
            </div>
        </div>
    </div>
</section>

<section id="data" class="container my-5 py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Data Pengaduan Terkini</h2>
        <div class="mx-auto" style="width: 60px; height: 4px; background: #0d6efd;"></div>
    </div>

    <div class="card shadow border-0 p-4">
        <div class="table-responsive">
            <table id="tabelPublik" class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($conn, "SELECT a.*, k.keterangan_kategori FROM aspirasi a JOIN kategori k ON a.id_kategori = k.id_kategori ORDER BY a.tanggal_pelaporan DESC");
                    while($row = mysqli_fetch_array($sql)){
                    ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row['tanggal_pelaporan'])) ?></td>
                        <td><?= $row['keterangan_kategori'] ?></td>
                        <td><?= $row['lokasi'] ?></td>
                        <td><?= substr($row['keterangan'], 0, 50) ?>...</td>
                        <td>
                            <?php if($row['status'] == 'menunggu'): ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Antrean</span>
                            <?php elseif($row['status'] == 'proses'): ?>
                                <span class="badge bg-info"><i class="fas fa-tools"></i> Dikerjakan</span>
                            <?php else: ?>
                                <span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 E-Pengaduan Sekolah - SMK AL-IRSYAD TEGAL</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelPublik').DataTable({
            "pageLength": 5,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
</body>
</html>