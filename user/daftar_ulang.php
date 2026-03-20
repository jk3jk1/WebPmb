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
$query = "SELECT cm.*, ps.nama_prodi, ps.kode_prodi 
          FROM calon_mahasiswa cm 
          LEFT JOIN program_studi ps ON cm.id_prodi = ps.id_prodi 
          WHERE cm.id_calon = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Cek apakah lulus
if ($user['status_kelulusan'] != 'lulus') {
    header('Location: pengumuman.php');
    exit();
}

// Jika sudah daftar ulang, redirect ke surat pernyataan
if ($user['status_daftar_ulang'] == 'sudah') {
    header('Location: surat_pernyataan.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = '../uploads/ktp/';
    
    // Buat folder jika belum ada
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $foto_ktp = '';
    
    // Upload KTP (opsional)
    if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] == 0) {
        $file_ext = strtolower(pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $file_name = 'ktp_' . $user_id . '_' . time() . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $file_path)) {
                $foto_ktp = $file_name;
            }
        } else {
            $error = 'Format file tidak valid. Gunakan JPG, PNG, atau PDF';
        }
    }
    
    if (empty($error)) {
        // Generate NIM
        $tahun = date('Y');
        $kode_prodi = $user['kode_prodi'];
        $nim = $tahun . $kode_prodi . str_pad($user_id, 4, '0', STR_PAD_LEFT);
        
        // Update database
        $tanggal_daftar_ulang = date('Y-m-d H:i:s');
        $update = "UPDATE calon_mahasiswa SET 
                   status_daftar_ulang = 'sudah', 
                   nim = '$nim', 
                   tanggal_daftar_ulang = '$tanggal_daftar_ulang'";
        
        if (!empty($foto_ktp)) {
            $update .= ", foto_ktp = '$foto_ktp'";
        }
        
        $update .= " WHERE id_calon = $user_id";
        
        if (mysqli_query($conn, $update)) {
            header('Location: surat_pernyataan.php');
            exit();
        } else {
            $error = 'Terjadi kesalahan: ' . mysqli_error($conn);
        }
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width="300" alt="Logo Universitas Nusantara"></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Formulir Daftar Ulang Mahasiswa Baru</p>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-menu no-print">
            <a href="dashboard.php">Dashboard</a>
            <a href="pengumuman.php">Pengumuman</a>
            <a href="daftar_ulang.php" class="active">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Formulir Daftar Ulang</h2>
            </div>

            <div class="alert alert-success">
                <strong>Selamat!</strong> Anda telah lulus seleksi. Silakan lengkapi form di bawah ini untuk melakukan daftar ulang dan mendapatkan NIM.
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card" style="background: #f7fafc;">
                <h3 style="color: #2d3748; margin-bottom: 20px;">Data Calon Mahasiswa</h3>
                
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 10px; color: #4a5568; width: 200px;">Nama Lengkap</td>
                        <td style="padding: 10px; color: #2d3748; font-weight: 600;">: <?php echo $user['nama_lengkap']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Email</td>
                        <td style="padding: 10px; color: #2d3748;">: <?php echo $user['email']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">No. Telepon</td>
                        <td style="padding: 10px; color: #2d3748;">: <?php echo $user['no_telepon']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Alamat</td>
                        <td style="padding: 10px; color: #2d3748;">: <?php echo $user['alamat']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Tanggal Lahir</td>
                        <td style="padding: 10px; color: #2d3748;">: <?php echo date('d F Y', strtotime($user['tanggal_lahir'])); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Jenis Kelamin</td>
                        <td style="padding: 10px; color: #2d3748;">: <?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Program Studi</td>
                        <td style="padding: 10px; color: #2d3748; font-weight: 600;">: <?php echo $user['nama_prodi']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; color: #4a5568;">Nomor Test</td>
                        <td style="padding: 10px; color: #2d3748; font-weight: 600;">: <?php echo $user['nomor_test']; ?></td>
                    </tr>
                </table>
            </div>

            <!-- UPLOAD DOKUMEN -->
            <form method="POST" enctype="multipart/form-data">
                <div class="card mt-20">
                    <h3 style="color: #ffffff; margin-bottom: 20px;"> Upload Dokumen (Opsional)</h3>
                    
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Foto/Scan KTP</label>
                        <input type="file" name="foto_ktp" class="form-control" accept=".jpg,.jpeg,.png,.pdf" style="background: #ffffff;">
                        <small style="color: #ffffff;">Format: JPG, PNG, atau PDF. Maksimal 2MB. (Opsional)</small>
                    </div>
                </div>

                <div class="card mt-20" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                    <h3 style="color: #78350f; margin-bottom: 15px;"> Pernyataan</h3>
                    <p style="color: #78350f; line-height: 1.8;">
                        Dengan melakukan daftar ulang, saya menyatakan bahwa:
                    </p>
                    <ul style="color: #78350f; line-height: 2; margin-left: 20px;">
                        <li>Semua data yang saya berikan adalah benar dan dapat dipertanggungjawabkan</li>
                        <li>Saya bersedia mengikuti semua peraturan yang berlaku di Universitas Nusantara</li>
                        <li>Saya siap untuk memulai perkuliahan sesuai jadwal yang ditentukan</li>
                        <li>Saya memahami bahwa pemalsuan data dapat mengakibatkan pembatalan penerimaan</li>
                    </ul>
                </div>

                <div class="text-center mt-20">
                    <button type="submit" class="btn btn-success" style="font-size: 1.1em; padding: 15px 50px;">
                        ✓ Konfirmasi Daftar Ulang & Dapatkan NIM
                    </button>
                    <br><br>
                    <a href="dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
