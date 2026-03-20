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
$success = '';

// Ambil data program studi
$prodi_query = "SELECT * FROM program_studi WHERE status = 'aktif' ORDER BY nama_prodi";
$prodi_result = mysqli_query($conn, $prodi_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $id_prodi = mysqli_real_escape_string($conn, $_POST['id_prodi']);
    $agree_terms = isset($_POST['agree_terms']) ? true : false;
    
    if (!$agree_terms) {
        $error = 'Anda harus menyetujui Privacy Policy & Terms of Service';
    } else {
        // Cek username
        $check_username = "SELECT * FROM calon_mahasiswa WHERE username = '$username'";
        $result_username = mysqli_query($conn, $check_username);
        
        if (mysqli_num_rows($result_username) > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            // Cek email
            $check_email = "SELECT * FROM calon_mahasiswa WHERE email = '$email'";
            $result_email = mysqli_query($conn, $check_email);
            
            if (mysqli_num_rows($result_email) > 0) {
                $error = 'Email sudah terdaftar!';
            } else {
                // Insert data
                $query = "INSERT INTO calon_mahasiswa (username, password, nama_lengkap, email, no_telepon, alamat, tanggal_lahir, jenis_kelamin, id_prodi) 
                          VALUES ('$username', '$password', '$nama_lengkap', '$email', '$no_telepon', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$id_prodi')";
                
                if (mysqli_query($conn, $query)) {
                    $success = 'Registrasi berhasil! Silakan login untuk melanjutkan.';
                } else {
                    $error = 'Registrasi gagal: ' . mysqli_error($conn);
                }
            }
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
    <title>Registrasi Calon Mahasiswa - Universitas Nusantara</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width="300" alt=""></div>
            <h1 style="color: white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color: white;">Formulir Pendaftaran Calon Mahasiswa Baru</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card" style="max-width: 700px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color: white;">Registrasi Akun Baru</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <br><a href="login.php" style="color: #22543d; text-decoration: underline; font-weight: bold;">Klik di sini untuk login</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label" style="color: white;">Username *</label>
                    <input type="text" name="username" class="form-control" required>
                    <small style="color: #e02525;">Username untuk login, minimal 4 karakter</small>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Password *</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                    <small style="color: #e81b1b;">Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">No. Telepon *</label>
                    <input type="text" name="no_telepon" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Alamat Lengkap *</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color: white;">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="color: white;">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: white;">Program Studi yang Diminati *</label>
                    <select name="id_prodi" class="form-control" required>
                        <option value="">Pilih Program Studi</option>
                        <?php while ($prodi = mysqli_fetch_assoc($prodi_result)): ?>
                            <option value="<?php echo $prodi['id_prodi']; ?>">
                                <?php echo $prodi['nama_prodi']; ?> (<?php echo $prodi['kode_prodi']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required>
                    <label for="agree_terms" style="color: white;">
                        Saya menyetujui <a href="#" onclick="showTerms(); return false;" style="color: #22d4ec;; text-decoration: underline;">Privacy Policy & Terms of Service</a> *
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Daftar Sekarang</button>

                <div class="text-center mt-20">
                    <p style="color: white;">Sudah punya akun? <a href="login.php" style="color: #22d4ec;; text-decoration: underline;">Login di sini</a></p>
                    <p><a href="../index.php" style="color: #b7dcf4;;">← Kembali ke Halaman Utama</a></p>
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
