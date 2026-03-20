<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT cm.*, ps.nama_prodi, ps.kode_prodi 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          WHERE cm.id_calon = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width ="300" alt=""></div>
            <h1 style="color: white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color: white;">Dashboard Calon Mahasiswa</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="nav-menu">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="test.php">Ujian Masuk</a>
            <a href="pengumuman.php">Pengumuman</a>
            <a href="daftar_ulang.php">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- THIRD HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Selamat Datang, <?php echo $user['nama_lengkap']; ?>! 👋</h2>
            </div>

            <!-- NOMOR TES -->
            <div class="grid-2">
                <div class="card" style="background: linear-gradient(135deg,  #3ebdd096 0%, #c2c2c2 100%); color: white;">
                    <h3 style="margin-bottom: 10px;"> Nomor Test Anda</h3>
                    <h1 style="font-size: 2.5em; margin: 15px 0;"><?php echo $user['nomor_test']; ?></h1>
                    <p>Simpan nomor ini untuk keperluan administrasi</p>
                </div>

                <!-- STATUS DAFTAR -->
                <div class="card" style="background: #f7fafc; border: 2px solid #e2e8f0;">
                    <h3 style="color: #2d3748; margin-bottom: 15px;">📊 Status Pendaftaran</h3>
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 8px 0; color: #4a5568;">Program Studi:</td>
                            <td style="padding: 8px 0; font-weight: 600; color: #2d3748;"><?php echo $user['nama_prodi']; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #4a5568;">Status Test:</td>
                            <td style="padding: 8px 0;">
                                <?php if ($user['status_test'] == 'sudah'): ?>
                                    <span class="badge badge-success">Sudah Mengikuti</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Belum Mengikuti</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #4a5568;">Status Kelulusan:</td>
                            <td style="padding: 8px 0;">
                                <?php if ($user['status_kelulusan'] == 'lulus'): ?>
                                    <span class="badge badge-success">LULUS</span>
                                <?php elseif ($user['status_kelulusan'] == 'tidak lulus'): ?>
                                    <span class="badge badge-danger">Tidak Lulus</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum Diumumkan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #4a5568;">Daftar Ulang:</td>
                            <td style="padding: 8px 0;">
                                <?php if ($user['status_daftar_ulang'] == 'sudah'): ?>
                                    <span class="badge badge-success">Sudah</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if (!empty($user['nim'])): ?>
                        <tr>
                            <td style="padding: 8px 0; color: #4a5568;">NIM:</td>
                            <td style="padding: 8px 0; font-weight: 600; color: #2d3748;"><?php echo $user['nim']; ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>


            <!-- TAHAPAN PENDAFTARAN -->
            <div class="card mt-20">
                <h3 style="color: #ffffff; margin-bottom: 20px;">Tahapan Pendaftaran</h3>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- Step 1 -->
                    <div style="display: flex; align-items: center; padding: 15px; background: #c6f6d5; border-radius: 8px; border-left: 4px solid #48bb78;">
                        <div style="width: 40px; height: 40px; background: #48bb78; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">✓</div>
                        <div>
                            <h4 style="color: #22543d; margin-bottom: 5px;">1. Registrasi Akun</h4>
                            <p style="color: #22543d; margin: 0;">Selesai - Akun Anda sudah aktif</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div style="display: flex; align-items: center; padding: 15px; background: <?php echo $user['status_test'] == 'sudah' ? '#c6f6d5' : '#feebc8'; ?>; border-radius: 8px; border-left: 4px solid <?php echo $user['status_test'] == 'sudah' ? '#48bb78' : '#ed8936'; ?>;">
                        <div style="width: 40px; height: 40px; background: <?php echo $user['status_test'] == 'sudah' ? '#48bb78' : '#ed8936'; ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                            <?php echo $user['status_test'] == 'sudah' ? '✓' : '2'; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2d3748; margin-bottom: 5px;">2. Ujian Masuk</h4>
                            <p style="color: #4a5568; margin: 0;">
                                <?php if ($user['status_test'] == 'sudah'): ?>
                                    Selesai - Anda telah mengikuti ujian
                                <?php else: ?>
                                    Silakan klik menu "Ujian Masuk" untuk memulai
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if ($user['status_test'] != 'sudah'): ?>
                        <a href="test.php" class="btn btn-primary">Mulai Test</a>
                        <?php endif; ?>
                    </div>

                    <!-- Step 3 -->
                    <div style="display: flex; align-items: center; padding: 15px; background: <?php echo $user['status_kelulusan'] == 'lulus' ? '#c6f6d5' : ($user['status_kelulusan'] == 'tidak lulus' ? '#fed7d7' : '#e2e8f0'); ?>; border-radius: 8px; border-left: 4px solid <?php echo $user['status_kelulusan'] == 'lulus' ? '#48bb78' : ($user['status_kelulusan'] == 'tidak lulus' ? '#f56565' : '#a0aec0'); ?>;">
                        <div style="width: 40px; height: 40px; background: <?php echo $user['status_kelulusan'] == 'lulus' ? '#48bb78' : ($user['status_kelulusan'] == 'tidak lulus' ? '#f56565' : '#a0aec0'); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                            <?php echo $user['status_kelulusan'] == 'lulus' ? '✓' : '3'; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2d3748; margin-bottom: 5px;">3. Pengumuman Hasil</h4>
                            <p style="color: #4a5568; margin: 0;">
                                <?php if ($user['status_kelulusan'] == 'lulus'): ?>
                                    Selamat! Anda dinyatakan LULUS
                                <?php elseif ($user['status_kelulusan'] == 'tidak lulus'): ?>
                                    Mohon maaf, Anda belum berhasil kali ini
                                <?php else: ?>
                                    Menunggu pengumuman dari panitia
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if ($user['status_test'] == 'sudah'): ?>
                        <a href="pengumuman.php" class="btn btn-info">Lihat Pengumuman</a>
                        <?php endif; ?>
                    </div>

                    <!-- Step 4 -->
                    <div style="display: flex; align-items: center; padding: 15px; background: <?php echo $user['status_daftar_ulang'] == 'sudah' ? '#c6f6d5' : '#e2e8f0'; ?>; border-radius: 8px; border-left: 4px solid <?php echo $user['status_daftar_ulang'] == 'sudah' ? '#48bb78' : '#a0aec0'; ?>;">
                        <div style="width: 40px; height: 40px; background: <?php echo $user['status_daftar_ulang'] == 'sudah' ? '#48bb78' : '#a0aec0'; ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px;">
                            <?php echo $user['status_daftar_ulang'] == 'sudah' ? '✓' : '4'; ?>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2d3748; margin-bottom: 5px;">4. Daftar Ulang</h4>
                            <p style="color: #4a5568; margin: 0;">
                                <?php if ($user['status_daftar_ulang'] == 'sudah'): ?>
                                    Selesai - Anda telah melakukan daftar ulang
                                <?php elseif ($user['status_kelulusan'] == 'lulus'): ?>
                                    Silakan lakukan daftar ulang untuk mendapatkan NIM
                                <?php else: ?>
                                    Hanya untuk peserta yang lulus
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if ($user['status_kelulusan'] == 'lulus' && $user['status_daftar_ulang'] != 'sudah'): ?>
                        <a href="daftar_ulang.php" class="btn btn-success">Daftar Ulang</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- INFORMASI -->
            <div class="card mt-20" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                <h4 style="color: #78350f; margin-bottom: 10px;">Informasi Penting</h4>
                <ul style="color: #78350f; line-height: 2; margin-left: 20px;">
                    <li>Pastikan Anda mengikuti ujian masuk sebelum batas waktu</li>
                    <li>Periksa pengumuman secara berkala untuk mengetahui hasil test</li>
                    <li>Jika lulus, segera lakukan daftar ulang untuk mendapatkan NIM</li>
                    <li>Simpan nomor test Anda dengan baik</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

