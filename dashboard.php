<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'admin') { header("Location: ../login.php"); }

// Hitung Statistik
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi"));
$menunggu = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='menunggu'"));
$proses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='proses'"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='selesai'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Dashboard Pengaduan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
    <!-- <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul> -->
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light pl-3">PENGADUAN</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="aspirasi_list.php" class="nav-link"><i class="nav-icon fas fa-list"></i><p>Data Aspirasi</p></a></li>
          <li class="nav-item">
    <a href="siswa_add.php" class="nav-link">
        <i class="nav-icon fas fa-user-plus"></i>
        <p>Kelola Siswa</p>
    </a>
</li>
<li class="nav-item"><a href="../logout.php" class="nav-link bg-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper p-3">
    <div class="content-header">
      <h1 class="m-0">Dashboard Ringkasan</h1>
    </div>
    
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner"><h3><?= $total ?></h3><p>Total Aspirasi</p></div>
              <div class="icon"><i class="fas fa-envelope"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner"><h3><?= $menunggu ?></h3><p>Menunggu</p></div>
              <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner"><h3><?= $proses ?></h3><p>Dalam Proses</p></div>
              <div class="icon"><i class="fas fa-tools"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner"><h3><?= $selesai ?></h3><p>Selesai</p></div>
              <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>