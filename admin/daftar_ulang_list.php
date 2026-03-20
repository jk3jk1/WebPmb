<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT cm.*, ps.nama_prodi 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          WHERE cm.status_daftar_ulang = 'sudah'
          ORDER BY cm.tanggal_daftar_ulang DESC";
$result = mysqli_query($conn, $query);
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Data Mahasiswa Daftar Ulang</p>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="calon_mahasiswa.php">Calon Mahasiswa</a>
            <a href="soal_test.php">Soal Test</a>
            <a href="hasil_test.php">Hasil Test</a>
            <a href="daftar_ulang_list.php" class="active">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Daftar Mahasiswa yang Sudah Daftar Ulang</h2>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Tanggal Daftar Ulang</th>
                            <th>KTP</th>
                        </tr>
                    </thead>

                    <!-- PHP -->
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $row['nim']; ?></strong></td>
                            <td><?php echo $row['nama_lengkap']; ?></td>
                            <td><?php echo $row['nama_prodi']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['no_telepon']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_daftar_ulang'])); ?></td>
                            <td>
                                <?php if ($row['foto_ktp']): ?>
                                    <a href="../uploads/ktp/<?php echo $row['foto_ktp']; ?>" target="_blank" class="btn btn-info" style="padding: 5px 10px; font-size: 0.85em;">Lihat</a>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
