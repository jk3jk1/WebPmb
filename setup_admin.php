<!-- UNTUK MERESET PASSWORD AKUN ATMIN -->
 <!-- php -->
<?php

require_once 'config/database.php';

$success = '';
$error = '';

// Cek apa sudah ada admin
$check = mysqli_query($conn, "SELECT * FROM admin WHERE username = 'admin'");
$admin_exists = mysqli_num_rows($check) > 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' || !$admin_exists) {
    // Hapus akun admin lama 
    mysqli_query($conn, "DELETE FROM admin WHERE username = 'admin'");
    
    // Buat password hash yang 
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Insert admin baru
    $query = "INSERT INTO admin (username, password, nama_lengkap, email) 
              VALUES ('admin', '$password', 'Administrator', 'admin@universitasnusantara.ac.id')";
    
    if (mysqli_query($conn, $query)) {
        $success = true;
    } else {
        $error = mysqli_error($conn);
    }
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Universitas Nusantara</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Setup Administrator</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Setup Akun Administrator</h2>
            </div>

            <!-- PHP -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h3 style="margin-bottom: 15px;">✓ Akun Admin Berhasil Dibuat!</h3>
                    <p><strong>Username:</strong> admin</p>
                    <p><strong>Password:</strong> admin123</p>
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #48bb78;">
                    <p style="margin-top: 20px;"><strong>⚠️ PENTING:</strong></p>
                    <ol style="text-align: left; margin-left: 20px; line-height: 2;">
                        <li>Silakan login dengan akun di atas</li>
                        <li>HAPUS file <code>setup_admin.php</code> ini setelah selesai untuk keamanan!</li>
                        <li>Ubah password admin setelah login pertama kali</li>
                    </ol>
                    <div style="margin-top: 30px;">
                        <a href="admin/login.php" class="btn btn-primary">Login sebagai Admin</a>
                        <a href="index.php" class="btn btn-secondary">Ke Halaman Utama</a>
                    </div>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">
                    <h3>Error!</h3>
                    <p><?php echo $error; ?></p>
                    <button onclick="location.reload()" class="btn btn-primary" style="margin-top: 15px;">Coba Lagi</button>
                </div>
            <?php elseif ($admin_exists): ?>
                <div class="alert alert-warning">
                    <h3>Akun Admin Sudah Ada</h3>
                    <p>Akun admin dengan username 'admin' sudah terdaftar di database.</p>
                    <p><strong>Jika Anda lupa password atau ingin reset akun admin, klik tombol di bawah:</strong></p>
                    <form method="POST" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin ingin reset akun admin? Password akan kembali ke admin123')">
                            Reset Akun Admin
                        </button>
                    </form>
                    <div style="margin-top: 20px;">
                        <a href="admin/login.php" class="btn btn-primary">Login Admin</a>
                        <a href="index.php" class="btn btn-secondary">Ke Halaman Utama</a>
                    </div>
                </div>
            <?php endif; ?>


            <!-- CATATAN -->
            <div class="card mt-20" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                <h4 style="color: #78350f; margin-bottom: 10px;">📌 Catatan Keamanan</h4>
                <ul style="color: #78350f; line-height: 2; margin-left: 20px;">
                    <li>File ini hanya untuk setup awal</li>
                    <li>Hapus file <code>setup_admin.php</code> setelah berhasil login</li>
                    <li>Ganti password default setelah login pertama</li>
                    <li>Jangan share password admin ke orang lain</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        code {
            background: #f7fafc;
            padding: 2px 8px;
            border-radius: 4px;
            color: #e53e3e;
            font-family: monospace;
        }
    </style>
</body>
</html>
