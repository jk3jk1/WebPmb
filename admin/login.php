<!-- PHP -->
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $agree_terms = isset($_POST['agree_terms']) ? true : false;
    
    if (!$agree_terms) {
        $error = 'Anda harus menyetujui Privacy Policy & Terms of Service';
    } else {
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];
                $_SESSION['role'] = 'admin';
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
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
    <title>Login Admin - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color: white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color: white;">Portal Administrator</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color: white;">Login Administrator</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" style="color: white;">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required>
                    <label for="agree_terms" style="color: white;">
                        Saya menyetujui <a href="#" onclick="showTerms(); return false;" style="color: #66ddea; text-decoration: underline;">Privacy Policy & Terms of Service</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Masuk</button>

                <div class="text-center mt-20">
                    <p><a href="../index.php" style="color:  #b7dcf4;">← Kembali ke Halaman Utama</a></p>
                </div>
            </form>

        
        </div>
    </div>

    <!-- PRIVACY POLICY -->
    <script>
        function showTerms() {
            alert('PRIVACY POLICY & TERMS OF SERVICE\n\n' +
                  '1. Akses admin hanya untuk pengelola sistem\n' +
                  '2. Jaga kerahasiaan username dan password\n' +
                  '3. Tidak boleh menyalahgunakan akses admin\n' +
                  '4. Bertanggung jawab atas semua aktivitas yang dilakukan\n' +
                  '5. Laporkan jika terjadi pelanggaran keamanan\n\n' +
                  'Dengan menyetujui, Anda telah membaca dan memahami kebijakan ini.');
        }
    </script>
</body>
</html>
