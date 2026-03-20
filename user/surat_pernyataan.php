<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT cm.*, ps.nama_prodi, ps.kode_prodi, ps.fakultas 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          WHERE cm.id_calon = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Cek apakah sudah daftar ulang
if ($user['status_daftar_ulang'] != 'sudah') {
    header('Location: daftar_ulang.php');
    exit();
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan - Universitas Nusantara</title>
    <!-- Link css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                padding: 20px;
            }
            .container {
                max-width: 100%;
            }
        }
        .surat-content {
            background: white;
            padding: 40px;
            line-height: 2;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #2d3748;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .signature-area {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print" style="margin-bottom: 20px;">
            <a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
            <button onclick="window.print()" class="btn btn-primary" style="float: right;">🖨️ Cetak Surat</button>
        </div>

        <!-- SURAT -->
        <div class="card surat-content">
            <div class="kop-surat">
                <div style="font-size: 2em; margin-bottom: 5px;"><img src="University Logo.png" width="300" alt="Logo Universitas Nusantara"></div>
                <h1 style="margin: 10px 0; font-size: 1.8em; color: #2d3748;">UNIVERSITAS NUSANTARA</h1>
                <p style="margin: 5px 0; color: #4a5568;">Jl. Pendidikan No. 123, Indonesia</p>
                <p style="margin: 5px 0; color: #4a5568;">Telp: (021) 1234567 | Email: info@universitasnusantara.ac.id</p>
                <p style="margin: 5px 0; color: #4a5568;">Website: www.universitasnusantara.ac.id</p>
            </div>

            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="text-decoration: underline; color: #2d3748;">SURAT PERNYATAAN</h2>
                <p style="color: #4a5568;">Nomor: <?php echo $user['nim']; ?>/SP/<?php echo date('Y'); ?></p>
            </div>

            <div style="color: #2d3748; text-align: justify;">
                <p>Yang bertanda tangan di bawah ini:</p>
                
                <table style="margin: 20px 0; width: 100%;">
                    <tr>
                        <td style="width: 200px; padding: 8px 0;">Nama</td>
                        <td style="padding: 8px 0;">: <strong><?php echo $user['nama_lengkap']; ?></strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;">Tempat, Tanggal Lahir</td>
                        <td style="padding: 8px 0;">: <?php echo date('d F Y', strtotime($user['tanggal_lahir'])); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;">Alamat</td>
                        <td style="padding: 8px 0;">: <?php echo $user['alamat']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;">No. Telepon</td>
                        <td style="padding: 8px 0;">: <?php echo $user['no_telepon']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;">Email</td>
                        <td style="padding: 8px 0;">: <?php echo $user['email']; ?></td>
                    </tr>
                </table>

                <p>Dengan ini menyatakan bahwa:</p>
                
                <ol style="margin: 20px 0; padding-left: 25px;">
                    <li>Telah diterima sebagai Mahasiswa Baru di <strong>Universitas Nusantara</strong></li>
                    <li>Program Studi: <strong><?php echo $user['nama_prodi']; ?></strong></li>
                    <li>Fakultas: <strong><?php echo $user['fakultas']; ?></strong></li>
                    <li>Nomor Induk Mahasiswa (NIM): <strong style="font-size: 1.2em; color: #667eea;"><?php echo $user['nim']; ?></strong></li>
                    <li>Tahun Akademik: <strong><?php echo date('Y'); ?>/<?php echo date('Y') + 1; ?></strong></li>
                </ol>

                <p>Saya menyatakan bahwa:</p>
                
                <ol style="margin: 20px 0; padding-left: 25px;">
                    <li>Semua data dan dokumen yang saya berikan adalah benar dan dapat dipertanggungjawabkan</li>
                    <li>Bersedia mengikuti seluruh peraturan dan tata tertib yang berlaku di Universitas Nusantara</li>
                    <li>Bersedia mengikuti proses perkuliahan sesuai dengan ketentuan yang berlaku</li>
                    <li>Apabila di kemudian hari ditemukan pemalsuan data atau dokumen, saya bersedia menerima sanksi sesuai peraturan yang berlaku</li>
                    <li>Bersedia membayar biaya pendidikan sesuai dengan ketentuan yang berlaku</li>
                </ol>

                <p>Demikian surat pernyataan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class="signature-area">
                <div class="signature-box">
                    <p style="margin-bottom: 80px;">Mengetahui,<br>Kepala Bagian Akademik</p>
                    <p style="border-top: 2px solid #2d3748; padding-top: 10px; font-weight: bold;">
                        Dr. H. Ahmad Susanto, M.Kom
                    </p>
                    <p style="color: #718096;">NIP. 196512251990031001</p>
                </div>
                
                <div class="signature-box">
                    <p style="margin-bottom: 10px;">Jakarta, <?php echo date('d F Y'); ?></p>
                    <p style="margin-bottom: 80px;">Yang Menyatakan,</p>
                    <p style="border-top: 2px solid #2d3748; padding-top: 10px; font-weight: bold;">
                        <?php echo $user['nama_lengkap']; ?>
                    </p>
                    <p style="color: #718096;">NIM. <?php echo $user['nim']; ?></p>
                </div>
            </div>

            <div style="margin-top: 40px; padding: 20px; background: #f0fff4; border: 2px solid #48bb78; border-radius: 8px;">
                <p style="color: #22543d; text-align: center; margin: 0; font-weight: 600;">
                    ✓ Dokumen ini telah diverifikasi secara digital oleh sistem Universitas Nusantara
                </p>
                <p style="color: #22543d; text-align: center; margin: 10px 0 0 0; font-size: 0.9em;">
                    Tanggal Daftar Ulang: <?php echo date('d F Y, H:i', strtotime($user['tanggal_daftar_ulang'])); ?> WIB
                </p>
            </div>
        </div>

        <!-- INFORMASI SELANJUTNYA -->
        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <div class="card" style="background: #bee3f8; border-left: 4px solid #4299e1;">
                <h3 style="color: #2c5282; margin-bottom: 15px;"> Informasi Selanjutnya</h3>
                <p style="color: #2c5282; line-height: 1.8;">
                    Selamat! Anda resmi menjadi mahasiswa Universitas Nusantara.<br>
                    Simpan dan cetak surat pernyataan ini sebagai bukti pendaftaran Anda.<br>
                    Informasi jadwal perkuliahan akan dikirimkan melalui email.
                </p>
            </div>
            
            <button onclick="window.print()" class="btn btn-primary" style="margin-top: 20px;">🖨️ Cetak Surat Pernyataan</button>
            <a href="dashboard.php" class="btn btn-success" style="margin-top: 20px;">✓ Selesai - Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
