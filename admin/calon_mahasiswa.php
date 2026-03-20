<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete = "DELETE FROM calon_mahasiswa WHERE id_calon = $id";
    if (mysqli_query($conn, $delete)) {
        $message = '<div class="alert alert-success">Data berhasil dihapus!</div>';
    }
}

// Ambil data calon mahasiswa
$query = "SELECT cm.*, ps.nama_prodi, ps.kode_prodi 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          ORDER BY cm.created_at DESC";
$result = mysqli_query($conn, $query);
?>

    <!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Mahasiswa - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Kelola Data Calon Mahasiswa</p>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="calon_mahasiswa.php" class="active">Calon Mahasiswa</a>
            <a href="soal_test.php">Soal Test</a>
            <a href="hasil_test.php">Hasil Test</a>
            <a href="daftar_ulang_list.php">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Data Calon Mahasiswa</h2>
            </div>

            <?php echo $message; ?>

            <div style="margin-bottom: 20px;">
                <a href="calon_mahasiswa_add.php" class="btn btn-success">+ Tambah Calon Mahasiswa</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Test</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Status Test</th>
                            <th>Status Kelulusan</th>
                            <th>Daftar Ulang</th>
                            <th>NIM</th>
                            <th>Aksi</th>
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
                            <td><?php echo $row['nomor_test']; ?></td>
                            <td><?php echo $row['nama_lengkap']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['nama_prodi']; ?></td>
                            <td>
                                <?php if ($row['status_test'] == 'sudah'): ?>
                                    <span class="badge badge-success">Sudah</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status_kelulusan'] == 'lulus'): ?>
                                    <span class="badge badge-success">Lulus</span>
                                <?php elseif ($row['status_kelulusan'] == 'tidak lulus'): ?>
                                    <span class="badge badge-danger">Tidak Lulus</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status_daftar_ulang'] == 'sudah'): ?>
                                    <span class="badge badge-success">Sudah</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['nim'] ?? '-'; ?></td>
                            <td>
                                <a href="calon_mahasiswa_edit.php?id=<?php echo $row['id_calon']; ?>" class="btn btn-info" style="padding: 8px 15px; font-size: 0.9em;">Edit</a>
                                <a href="?delete=<?php echo $row['id_calon']; ?>" class="btn btn-danger" style="padding: 8px 15px; font-size: 0.9em;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
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
