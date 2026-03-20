<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Statistik
$total_pendaftar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM calon_mahasiswa"))['total'];
$total_lulus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM calon_mahasiswa WHERE status_kelulusan = 'lulus'"))['total'];
$total_tidak_lulus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM calon_mahasiswa WHERE status_kelulusan = 'tidak lulus'"))['total'];
$total_daftar_ulang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM calon_mahasiswa WHERE status_daftar_ulang = 'sudah'"))['total'];
$total_soal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM soal_test WHERE status = 'aktif'"))['total'];
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Universitas Nusantara</title>
    
    <!-- Font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Link -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Dashboard Administrator</p>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-menu">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="calon_mahasiswa.php">Calon Mahasiswa</a>
            <a href="soal_test.php">Soal Test</a>
            <a href="hasil_test.php">Hasil Test</a>
            <a href="daftar_ulang_list.php">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Selamat Datang, <?php echo $_SESSION['admin_nama']; ?>! 👨‍💼</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <!-- Total Pendaftar -->
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div style="font-size: 3em; margin-bottom: 10px;" align="center"><i class="fa-solid fa-user"></i></div>
                    <h3 style="font-size: 2.5em; margin: 10px 0;" align="center"><?php echo $total_pendaftar; ?></h3>
                    <p style="font-size: 1.1em;" align="center">Total Pendaftar</p>
                </div>

                <!-- Total Lulus -->
                <div class="card" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white;">
                    <div style="font-size: 3em; margin-bottom: 10px;" align="center"><i class="fa-solid fa-check"></i></div>
                    <h3 style="font-size: 2.5em; margin: 10px 0;" align="center"><?php echo $total_lulus; ?></h3>
                    <p style="font-size: 1.1em;" align="center">Mahasiswa Lulus</p>
                </div>

                <!-- Total Tidak Lulus -->
                <div class="card" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white;">
                    <div style="font-size: 3em; margin-bottom: 10px;" align="center"><i class="fa-solid fa-x"></i></div>
                    <h3 style="font-size: 2.5em; margin: 10px 0;" align="center"><?php echo $total_tidak_lulus; ?></h3>
                    <p style="font-size: 1.1em;" align="center">Tidak Lulus</p>
                </div>

                <!-- Total Daftar Ulang -->
                <div class="card" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white;">
                    <div style="font-size: 3em; margin-bottom: 10px;" align="center"><i class="fa-solid fa-list"></i></div>
                    <h3 style="font-size: 2.5em; margin: 10px 0;" align="center"><?php echo $total_daftar_ulang; ?></h3>
                    <p style="font-size: 1.1em;" align="center">Sudah Daftar Ulang</p>
                </div>

                <!-- Total Soal -->
                <div class="card" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white;">
                    <div style="font-size: 3em; margin-bottom: 10px;" align="center"><i class="fa-solid fa-book"></i></div>
                    <h3 style="font-size: 2.5em; margin: 10px 0;" align="center"><?php echo $total_soal; ?></h3>
                    <p style="font-size: 1.1em;" align="center">Soal Aktif</p>
                </div>
            </div>

            <!-- MENU ADMINISTRASI -->
            <div class="card mt-20">
                <h3 style="color: #ffffff; margin-bottom: 20px;">📊 Menu Administrasi</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="calon_mahasiswa.php" class="btn btn-primary">
                         Kelola Calon Mahasiswa
                    </a>
                    <a href="soal_test.php" class="btn btn-info">
                        Kelola Soal Test
                    </a>
                    <a href="hasil_test.php" class="btn btn-success">
                        Lihat Hasil Test
                    </a>
                    <a href="daftar_ulang_list.php" class="btn btn-warning">
                         Data Daftar Ulang
                    </a>
                </div>
            </div>

            <!-- Data Terbaru -->
            <div class="card mt-20">
                <h3 style="color: #ffffff; margin-bottom: 20px;"> Pendaftar Terbaru</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Program Studi</th>
                                <th>Status Test</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP -->
                            <?php
                            $query_terbaru = "SELECT cm.*, ps.nama_prodi 
                                            FROM calon_mahasiswa cm 
                                            LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
                                            ORDER BY cm.created_at DESC 
                                            LIMIT 5";
                            $result_terbaru = mysqli_query($conn, $query_terbaru);
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result_terbaru)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
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
                                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
