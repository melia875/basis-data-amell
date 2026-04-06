<?php
session_start();
include '../config/koneksi.php';

// Proteksi Halaman
if ($_SESSION['role'] != 'admin') { 
    header("Location: ../login.php"); 
    exit;
}

// 1. PROSES TAMBAH SISWA
if (isset($_POST['simpan'])) {
    $nis      = mysqli_real_escape_string($conn, $_POST['nis']);
    $kelas    = mysqli_real_escape_string($conn, $_POST['kelas']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIS sudah terdaftar!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO siswa (nis, kelas, password) VALUES ('$nis', '$kelas', '$password')");
        echo "<script>alert('Siswa berhasil ditambahkan!'); window.location='siswa_add.php';</script>";
    }
}

// 2. PROSES EDIT SISWA
if (isset($_POST['update'])) {
    $nis_lama = $_POST['nis_lama'];
    $kelas    = mysqli_real_escape_string($conn, $_POST['kelas']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    mysqli_query($conn, "UPDATE siswa SET kelas='$kelas', password='$password' WHERE nis='$nis_lama'");
    echo "<script>alert('Data siswa diperbarui!'); window.location='siswa_add.php';</script>";
}

// 3. PROSES HAPUS SISWA
if (isset($_GET['hapus'])) {
    $nis = $_GET['hapus'];
    // Catatan: Jika NIS sudah mengirim aspirasi, ini mungkin gagal karena relasi database.
    $query = mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'");
    if($query){
        echo "<script>alert('Siswa berhasil dihapus!'); window.location='siswa_add.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus! Siswa mungkin sudah memiliki data aspirasi.'); window.location='siswa_add.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Kelola Siswa</title>
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
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link"><span class="brand-text font-weight-light pl-3">PENGADUAN</span></a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="aspirasi_list.php" class="nav-link"><i class="nav-icon fas fa-list"></i><p>Data Aspirasi</p></a></li>
          <li class="nav-item"><a href="siswa_add.php" class="nav-link active"><i class="nav-icon fas fa-user-plus"></i><p>Kelola Siswa</p></a></li>
          <li class="nav-item">
<li class="nav-item"><a href="../logout.php" class="nav-link bg-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper p-4">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header"><h3 class="card-title">Tambah Siswa</h3></div>
          <form method="POST">
            <div class="card-body">
              <div class="form-group"><label>NIS</label><input type="text" name="nis" class="form-control" required></div>
              <div class="form-group"><label>Kelas</label><input type="text" name="kelas" class="form-control" required></div>
              <div class="form-group"><label>Password</label><input type="text" name="password" class="form-control" required></div>
            </div>
            <div class="card-footer"><button type="submit" name="simpan" class="btn btn-primary w-100">Simpan</button></div>
          </form>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card card-outline card-info">
          <div class="card-header"><h3 class="card-title">Daftar Siswa</h3></div>
          <div class="card-body p-0">
            <table class="table table-sm table-striped">
              <thead>
                <tr>
                  <th>NIS</th>
                  <th>Kelas</th>
                  <th>Password</th>
                  <th style="width: 150px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $list = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nis ASC");
                while($s = mysqli_fetch_array($list)){
                ?>
                <tr>
                  <td><?= $s['nis'] ?></td>
                  <td><?= $s['kelas'] ?></td>
                  <td><?= $s['password'] ?></td>
                  <td>
                    <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#edit<?= $s['nis'] ?>"><i class="fas fa-edit"></i></button>
                    <a href="?hapus=<?= $s['nis'] ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus siswa ini?')"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>

                <div class="modal fade" id="edit<?= $s['nis'] ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="POST">
                        <div class="modal-header"><h4 class="modal-title">Edit Siswa</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                        <div class="modal-body">
                          <input type="hidden" name="nis_lama" value="<?= $s['nis'] ?>">
                          <div class="form-group"><label>NIS (Tidak bisa ubah)</label><input type="text" class="form-control" value="<?= $s['nis'] ?>" disabled></div>
                          <div class="form-group"><label>Kelas</label><input type="text" name="kelas" class="form-control" value="<?= $s['kelas'] ?>" required></div>
                          <div class="form-group"><label>Password</label><input type="text" name="password" class="form-control" value="<?= $s['password'] ?>" required></div>
                        </div>
                        <div class="modal-footer"><button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button></div>
                      </form>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>