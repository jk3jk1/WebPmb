<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Ambil data program studi
$prodi_query = "SELECT * FROM program_studi WHERE status = 'aktif' ORDER BY nama_prodi";
$prodi_result = mysqli_query($conn, $prodi_query);

// Ambil data calon mahasiswa
$query = "SELECT * FROM calon_mahasiswa WHERE id_calon = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: calon_mahasiswa.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $id_prodi = mysqli_real_escape_string($conn, $_POST['id_prodi']);
    
    $update_query = "UPDATE calon_mahasiswa SET 
                    nama_lengkap = '$nama_lengkap',
                    email = '$email',
                    no_telepon = '$no_telepon',
                    alamat = '$alamat',
                    tanggal_lahir = '$tanggal_lahir',
                    jenis_kelamin = '$jenis_kelamin',
                    id_prodi = '$id_prodi'
                    WHERE id_calon = $id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = 'Data berhasil diupdate!';
        // Refresh data
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
    } else {
        $error = 'Gagal update data: ' . mysqli_error($conn);
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Calon Mahasiswa - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Edit Data Calon Mahasiswa</p>
        </div>

        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Edit Data Calon Mahasiswa</h2>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label" style="color:white;">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $data['nama_lengkap']; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" value="<?php echo $data['no_telepon']; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Alamat</label>
                    <textarea name="alamat" class="form-control" required><?php echo $data['alamat']; ?></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo $data['tanggal_lahir']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="color:white;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" <?php echo $data['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo $data['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Program Studi</label>
                    <select name="id_prodi" class="form-control" required>
                        <?php 
                        mysqli_data_seek($prodi_result, 0);
                        while ($prodi = mysqli_fetch_assoc($prodi_result)): 
                        ?>
                            <option value="<?php echo $prodi['id_prodi']; ?>" <?php echo $data['id_prodi'] == $prodi['id_prodi'] ? 'selected' : ''; ?>>
                                <?php echo $prodi['nama_prodi']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="text-center mt-20">
                    <button type="submit" class="btn btn-success">Update Data</button>
                    <a href="calon_mahasiswa.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
