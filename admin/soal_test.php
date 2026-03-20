<!-- PHP -->
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM soal_test WHERE id_soal = $id");
    $message = '<div class="alert alert-success">Soal berhasil dihapus!</div>';
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
    $pilihan_a = mysqli_real_escape_string($conn, $_POST['pilihan_a']);
    $pilihan_b = mysqli_real_escape_string($conn, $_POST['pilihan_b']);
    $pilihan_c = mysqli_real_escape_string($conn, $_POST['pilihan_c']);
    $pilihan_d = mysqli_real_escape_string($conn, $_POST['pilihan_d']);
    $jawaban_benar = mysqli_real_escape_string($conn, $_POST['jawaban_benar']);
    
    if (isset($_POST['id_soal']) && !empty($_POST['id_soal'])) {
        $id = (int)$_POST['id_soal'];
        $query = "UPDATE soal_test SET pertanyaan='$pertanyaan', pilihan_a='$pilihan_a', pilihan_b='$pilihan_b', pilihan_c='$pilihan_c', pilihan_d='$pilihan_d', jawaban_benar='$jawaban_benar' WHERE id_soal=$id";
        $msg = 'updated';
    } else {
        $query = "INSERT INTO soal_test (pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar) VALUES ('$pertanyaan', '$pilihan_a', '$pilihan_b', '$pilihan_c', '$pilihan_d', '$jawaban_benar')";
        $msg = 'added';
    }
    
    if (mysqli_query($conn, $query)) {
        $message = '<div class="alert alert-success">Soal berhasil ' . ($msg == 'added' ? 'ditambahkan' : 'diupdate') . '!</div>';
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_result = mysqli_query($conn, "SELECT * FROM soal_test WHERE id_soal = $id");
    $edit_data = mysqli_fetch_assoc($edit_result);
}

$query = "SELECT * FROM soal_test ORDER BY id_soal DESC";
$result = mysqli_query($conn, $query);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal Test - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="../user/University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Kelola Soal Test</p>
        </div>

        <!-- DASHBOARD -->
        <div class="nav-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="calon_mahasiswa.php">Calon Mahasiswa</a>
            <a href="soal_test.php" class="active">Soal Test</a>
            <a href="hasil_test.php">Hasil Test</a>
            <a href="daftar_ulang_list.php">Daftar Ulang</a>
            <a href="logout.php" style="margin-left: auto;">Logout</a>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;"><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Soal Test</h2>
            </div>

            <?php echo $message; ?>

            <form method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id_soal" value="<?php echo $edit_data['id_soal']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label" style="color:white;">Pertanyaan</label>
                    <textarea name="pertanyaan" class="form-control" required><?php echo $edit_data['pertanyaan'] ?? ''; ?></textarea>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Pilihan A</label>
                        <input type="text" name="pilihan_a" class="form-control" value="<?php echo $edit_data['pilihan_a'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Pilihan B</label>
                        <input type="text" name="pilihan_b" class="form-control" value="<?php echo $edit_data['pilihan_b'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Pilihan C</label>
                        <input type="text" name="pilihan_c" class="form-control" value="<?php echo $edit_data['pilihan_c'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:white;">Pilihan D</label>
                        <input type="text" name="pilihan_d" class="form-control" value="<?php echo $edit_data['pilihan_d'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="color:white;">Jawaban Benar</label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="">Pilih Jawaban</option>
                        <option value="A" <?php echo ($edit_data['jawaban_benar'] ?? '') == 'A' ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?php echo ($edit_data['jawaban_benar'] ?? '') == 'B' ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?php echo ($edit_data['jawaban_benar'] ?? '') == 'C' ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?php echo ($edit_data['jawaban_benar'] ?? '') == 'D' ? 'selected' : ''; ?>>D</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success"><?php echo $edit_data ? 'Update' : 'Tambah'; ?> Soal</button>
                <?php if ($edit_data): ?>
                    <a href="soal_test.php" class="btn btn-secondary">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- THIRD HEADER FIRST PAGE -->
        <div class="card mt-20">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Daftar Soal Test</h2>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban Benar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo substr($row['pertanyaan'], 0, 100); ?>...</td>
                            <td><strong><?php echo $row['jawaban_benar']; ?></strong></td>
                            <td><span class="badge badge-success"><?php echo ucfirst($row['status']); ?></span></td>
                            <td>
                                <a href="?edit=<?php echo $row['id_soal']; ?>" class="btn btn-info" style="padding: 8px 15px; font-size: 0.9em;">Edit</a>
                                <a href="?delete=<?php echo $row['id_soal']; ?>" class="btn btn-danger" style="padding: 8px 15px; font-size: 0.9em;" onclick="return confirm('Yakin ingin menghapus soal ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
