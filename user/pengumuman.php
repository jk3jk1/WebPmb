<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user dengan join program studi
$query = "SELECT cm.*, ps.nama_prodi, ps.kode_prodi 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          WHERE cm.id_calon = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Jika belum test, redirect
if ($user['status_test'] != 'sudah') {
    header('Location: test.php');
    exit();
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Hasil - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width="300" alt="Logo Universitas Nusantara"></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Jl. Pendidikan No. 123, Indonesia</p>
            <p style="color:white;">Email: info@universitasnusantara.ac.id | Telp: (021) 1234567</p>
            <p style="color:white;">Website: <a href="http://www.universitasnusantara.ac.id" target="_blank" style="color: #ff6b6b;">www.universitasnusantara.ac.id</a></p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div style="text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                <h2 style="color: #e9f50d; font-size: 1.8em; margin-bottom: 10px;">PENGUMUMAN HASIL SELEKSI UJIAN MASUK</h2>
            </div>

            <?php if ($user['status_kelulusan'] == 'lulus'): ?>
                <div class="result-card">
                    <div class="result-icon">✓</div>
                    <div class="result-title">SELAMAT! ANDA DINYATAKAN LULUS</div>
                    <p style="color: #22543d; font-size: 1.1em; margin-top: 10px;">
                        Terima kasih atas partisipasi Anda dalam mengikuti ujian seleksi masuk
                    </p>
                </div>
            <?php else: ?>
                <div class="result-card failed">
                    <div class="result-icon">✗</div>
                    <div class="result-title">Mohon Maaf, Anda Belum Berhasil</div>
                    <p style="color: #742a2a; font-size: 1.1em; margin-top: 10px;">
                        Jangan berkecil hati, Anda dapat mencoba lagi di periode berikutnya
                    </p>
                </div>
            <?php endif; ?>

            <!-- DETIAL INFORMASI -->
            <div class="card" style="background: #f7fafc;">
                <h3 style="color: #2d3748; margin-bottom: 20px; text-align: center;">Detail Informasi</h3>
                
                <table style="width: 100%; max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Nama Lengkap</td>
                        <td style="padding: 12px; color: #2d3748; font-weight: 600;"><?php echo $user['nama_lengkap']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Email</td>
                        <td style="padding: 12px; color: #2d3748;"><?php echo $user['email']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Program Studi</td>
                        <td style="padding: 12px; color: #2d3748; font-weight: 600;"><?php echo $user['nama_prodi']; ?></td>
                    </tr>
                    <tr style="background: #fff;">
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Nomor Test</td>
                        <td style="padding: 12px; color: #2d3748; font-weight: 600; font-size: 1.2em;"><?php echo $user['nomor_test']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Nilai Minimum Kelulusan</td>
                        <td style="padding: 12px; color: #2d3748;"><?php echo $user['nilai_minimum']; ?> / 100</td>
                    </tr>
                    <tr style="background: #fff;">
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Tanggal Ujian</td>
                        <td style="padding: 12px; color: #2d3748;">
                            <?php 
                            if ($user['tanggal_test']) {
                                echo date('d F Y, H:i', strtotime($user['tanggal_test'])) . ' WIB';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; color: #4a5568; font-weight: 500;">Status Kelulusan</td>
                        <td style="padding: 12px;">
                            <?php if ($user['status_kelulusan'] == 'lulus'): ?>
                                <span class="badge badge-success" style="font-size: 1.1em; padding: 8px 20px;">LULUS</span>
                            <?php else: ?>
                                <span class="badge badge-danger" style="font-size: 1.1em; padding: 8px 20px;">TIDAK LULUS</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- INFORMASI -->
            <?php if ($user['status_kelulusan'] == 'lulus'): ?>
                <div class="card mt-20" style="background: #fffbeb; border: 2px solid #f59e0b;">
                    <h3 style="color: #78350f; margin-bottom: 15px;"> INFORMASI PENTING</h3>
                    <ul style="color: #78350f; line-height: 2; margin-left: 20px;">
                        <li>Simpan dan catat NIM Anda untuk keperluan administrasi selanjutnya</li>
                        <li>Silakan melakukan <strong>daftar ulang</strong> melalui sistem online</li>
                        <li>Proses registrasi ulang akan diinformasikan melalui email</li>
                        <li>Hubungi bagian akademik untuk informasi lebih lanjut</li>
                    </ul>
                </div>

                <div class="text-center mt-20 no-print">
                    <a href="daftar_ulang.php" class="btn btn-success" style="font-size: 1.1em; padding: 15px 40px;">
                         Lakukan Daftar Ulang Sekarang
                    </a>
                </div>
            <?php endif; ?>

            <div class="text-center mt-20 no-print">
                <button onclick="window.print()" class="btn btn-info">🖨️ Cetak Pengumuman</button>
                <a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
            </div>

            <div class="card mt-20" style="background: #f0fff4; border-left: 4px solid #48bb78;">
                <p style="color: #22543d; text-align: center; margin: 0;">
                    Dokumen ini adalah bukti resmi kelulusan Anda
                </p>
            </div>
        </div>
    </div>

    <!-- PRINT -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
</body>
</html>
