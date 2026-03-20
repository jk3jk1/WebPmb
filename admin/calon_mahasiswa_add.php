<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

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
    
    $check_username = "SELECT * FROM calon_mahasiswa WHERE username = '$username'";
    if (mysqli_num_rows(mysqli_query($conn, $check_username)) > 0) {
        $error = 'Username sudah digunakan!';
    } else {
        $query = "INSERT INTO calon_mahasiswa (username, password, nama_lengkap, email, no_telepon, alamat, tanggal_lahir, jenis_kelamin, id_prodi) VALUES ('$username', '$password', '$nama_lengkap', '$email', '$no_telepon', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$id_prodi')";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan data: ' . mysqli_error($conn);
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
    <title>Tambah Calon Mahasiswa - Admin</title>
    <!-- Link Css -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Tambah Calon Mahasiswa</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Tambah Data Calon Mahasiswa</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <br><a href="calon_mahasiswa.php">Lihat Daftar Calon Mahasiswa</a>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label"style="color:white;">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:white;">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Program Studi</label>
                    <select name="id_prodi" class="form-control" required>
                        <option value="">Pilih Program Studi</option>
                        <?php while ($prodi = mysqli_fetch_assoc($prodi_result)): ?>
                            <option value="<?php echo $prodi['id_prodi']; ?>"><?php echo $prodi['nama_prodi']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="text-center mt-20">
                    <button type="submit" class="btn btn-success">Tambah Data</button>
                    <a href="calon_mahasiswa.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
