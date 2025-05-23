<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koneksi Database</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Koneksi Database dengan php</h2>
    <br>
    <table border="1" cellpadding="10">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
        </tr>

        <?php
        $no = 1;
        $query = mysqli_query($conn, "SELECT * FROM pengunjung");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>".$row['nama']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "</tr>";
    }
    ?>
    </table>
</body>
</html>