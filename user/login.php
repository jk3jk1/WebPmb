<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
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
        $query = "SELECT * FROM calon_mahasiswa WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id_calon'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = 'user';
                
                // Generate nomor test 
                if (empty($user['nomor_test'])) {
                    $tahun = date('Y');
                    $nomor_test = $tahun . str_pad($user['id_calon'], 6, '0', STR_PAD_LEFT);
                    
                    $update = "UPDATE calon_mahasiswa SET nomor_test = '$nomor_test' WHERE id_calon = {$user['id_calon']}";
                    mysqli_query($conn, $update);
                }
                
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
    <title>Login Calon Mahasiswa - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width ="300" alt=""></div>
            <h1 style="color: white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color: white;">Portal Calon Mahasiswa Baru</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color: white;">Login Calon Mahasiswa</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" style="color: white;">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required>
                    <label for="agree_terms" style="color: white;">
                        Saya menyetujui <a href="#" onclick="showTerms(); return false;" style="color: #22d4ec; text-decoration: underline;">Privacy Policy & Terms of Service</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Masuk</button>

                <div class="text-center mt-20">
                    <p style="color: white;">Belum punya akun? <a href="register.php" style="color: #66ddea; text-decoration: underline;">Daftar di sini</a></p>
                    <p><a href="../index.php" style="color: #b7dcf4;">← Kembali ke Halaman Utama</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- PRIVACY POLICY -->
    <script>
        function showTerms() {
            alert('PRIVACY POLICY & TERMS OF SERVICE\n\n' +
                  '1. Data pribadi Anda akan dijaga kerahasiaannya\n' +
                  '2. Informasi yang Anda berikan hanya digunakan untuk keperluan pendaftaran\n' +
                  '3. Anda bertanggung jawab atas kebenaran data yang diberikan\n' +
                  '4. Universitas berhak membatalkan pendaftaran jika ditemukan data palsu\n' +
                  '5. Keputusan panitia bersifat final dan tidak dapat diganggu gugat\n\n' +
                  'Dengan menyetujui, Anda telah membaca dan memahami kebijakan ini.');
        }
    </script>
</body>
</html>
