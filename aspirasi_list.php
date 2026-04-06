<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'admin') { header("Location: ../login.php"); }

// Proses Update Status & Feedback
if (isset($_POST['update_aspirasi'])) {
    $id = $_POST['id_aspirasi'];
    $status = $_POST['status'];
    $feedback = $_POST['feedback'];

    $query = mysqli_query($conn, "UPDATE aspirasi SET status='$status', feedback='$feedback' WHERE id_aspirasi='$id'");
    if ($query) {
        echo "<script>alert('Status berhasil diperbarui!'); window.location='aspirasi_list.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | List Aspirasi</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link"><span class="brand-text font-weight-light pl-3">E-PENGADUAN</span></a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="aspirasi_list.php" class="nav-link active"><i class="nav-icon fas fa-list"></i><p>Data Aspirasi</p></a></li>
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
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h3 class="card-title">Manajemen Keluhan Siswa</h3>
      </div>
      <div class="card-body">
        <table id="tabelAspirasi" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>NIS</th>
              <th>Kelas</th>
              <th>Kategori</th>
              <th>Lokasi</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT a.*, s.kelas, k.keterangan_kategori 
                    FROM aspirasi a 
                    JOIN siswa s ON a.nis = s.nis 
                    JOIN kategori k ON a.id_kategori = k.id_kategori";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($res)){
            ?>
            <tr>
              <td><?= $row['tanggal_pelaporan'] ?></td>
              <td><?= $row['nis'] ?></td>
              <td><?= $row['kelas'] ?></td>
              <td><?= $row['keterangan_kategori'] ?></td>
              <td><?= $row['lokasi'] ?></td>
              <td><?= $row['keterangan'] ?></td>
              <td>
                <?php 
                  $c = ($row['status'] == 'menunggu') ? 'warning' : (($row['status'] == 'proses') ? 'info' : 'success');
                ?>
                <span class="badge badge-<?= $c ?>"><?= strtoupper($row['status']) ?></span>
              </td>
              <td>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEdit<?= $row['id_aspirasi'] ?>">
                  <i class="fas fa-edit"></i> Respon
                </button>
              </td>
            </tr>

            <div class="modal fade" id="modalEdit<?= $row['id_aspirasi'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title">Berikan Umpan Balik</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                      <div class="mb-3">
                        <label>Isi Keluhan:</label>
                        <p class="text-muted small">"<?= $row['keterangan'] ?>"</p>
                      </div>
                      <div class="form-group">
                        <label>Ubah Status</label>
                        <select name="status" class="form-control">
                          <option value="menunggu" <?= $row['status']=='menunggu'?'selected':'' ?>>Menunggu</option>
                          <option value="proses" <?= $row['status']=='proses'?'selected':'' ?>>Proses</option>
                          <option value="selesai" <?= $row['status']=='selesai'?'selected':'' ?>>Selesai</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Tanggapan / Umpan Balik</label>
                        <textarea name="feedback" class="form-control" rows="3" required><?= $row['feedback'] ?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="update_aspirasi" class="btn btn-success">Simpan Perubahan</button>
                    </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).ready(function() {
    $('#tabelAspirasi').DataTable({
        "order": [[ 0, "desc" ]], // Urutan tanggal terbaru
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
  });
</script>
</body>
</html>