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
$user_query = "SELECT * FROM calon_mahasiswa WHERE id_calon = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Cek apakah sudah pernah test
if ($user['status_test'] == 'sudah') {
    header('Location: pengumuman.php');
    exit();
}

$message = '';

// Proses submit jawaban
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total_benar = 0;
    $total_soal = 0;
    
    // Ambil semua soal aktif
    $soal_query = "SELECT * FROM soal_test WHERE status = 'aktif' ORDER BY id_soal";
    $soal_result = mysqli_query($conn, $soal_query);
    
    while ($soal = mysqli_fetch_assoc($soal_result)) {
        $total_soal++;
        $id_soal = $soal['id_soal'];
        $jawaban_user = isset($_POST['jawaban_' . $id_soal]) ? $_POST['jawaban_' . $id_soal] : '';
        
        $is_correct = ($jawaban_user == $soal['jawaban_benar']) ? 1 : 0;
        if ($is_correct) {
            $total_benar++;
        }
        
        // Simpan jawaban
        $insert_jawaban = "INSERT INTO jawaban_test (id_calon, id_soal, jawaban, is_correct) 
                          VALUES ($user_id, $id_soal, '$jawaban_user', $is_correct)";
        mysqli_query($conn, $insert_jawaban);
    }
    
    // Hitung nilai
    $nilai = ($total_soal > 0) ? round(($total_benar / $total_soal) * 100) : 0;
    
    // Tentukan status kelulusan
    $status_kelulusan = ($nilai >= $user['nilai_minimum']) ? 'lulus' : 'tidak lulus';
    
    // Update data user
    $tanggal_test = date('Y-m-d H:i:s');
    $update_query = "UPDATE calon_mahasiswa SET 
                     status_test = 'sudah', 
                     nilai_test = $nilai, 
                     status_kelulusan = '$status_kelulusan',
                     tanggal_test = '$tanggal_test'
                     WHERE id_calon = $user_id";
    
    if (mysqli_query($conn, $update_query)) {
        header('Location: pengumuman.php');
        exit();
    }
}

// Ambil soal-soal
$soal_query = "SELECT * FROM soal_test WHERE status = 'aktif' ORDER BY RAND()";
$soal_result = mysqli_query($conn, $soal_query);
$jumlah_soal = mysqli_num_rows($soal_result);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Masuk - Universitas Nusantara</title>
    <!-- link css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .timer-count {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>

    <!-- TIMER UJIAN -->
    <div class="timer no-print">
        <div style="text-align: center;">
            <div style="color: #4a5568; margin-bottom: 5px;">Waktu Tersisa</div>
            <div class="timer-count" id="timer">60:00</div>
        </div>
    </div>

    <!-- HEADER FIRST PAGE -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="University Logo.png" width="300" alt=""></div>
            <h1 style="color:white;">UNIVERSITAS NUSANTARA</h1>
            <p style="color:white;">Ujian Seleksi Masuk</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:white;">Ujian Masuk - Tes Pengetahuan IT</h2>
            </div>

            <!-- INSTRUKSI -->
            <div class="alert alert-info">
                <strong>Instruksi:</strong><br>
                • Waktu mengerjakan: 60 menit<br>
                • Jumlah soal: <?php echo $jumlah_soal; ?> soal<br>
                • Passing grade: <?php echo $user['nilai_minimum']; ?> dari 100<br>
                • Pilih salah satu jawaban yang paling tepat<br>
                • Pastikan semua soal terjawab sebelum submit
            </div>

            <!-- SOAL UJIAN -->
            <form method="POST" id="testForm" onsubmit="return confirmSubmit()">
                <?php 
                $no = 1;
                mysqli_data_seek($soal_result, 0); // Reset pointer
                while ($soal = mysqli_fetch_assoc($soal_result)): 
                ?>
                <div class="question-card">
                    <div class="question-number">Soal <?php echo $no; ?> dari <?php echo $jumlah_soal; ?></div>
                    <div class="question-text"><?php echo $soal['pertanyaan']; ?></div>
                    
                    <div class="option-group">
                        <label>
                            <input type="radio" name="jawaban_<?php echo $soal['id_soal']; ?>" value="A" required>
                            <span>A. <?php echo $soal['pilihan_a']; ?></span>
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label>
                            <input type="radio" name="jawaban_<?php echo $soal['id_soal']; ?>" value="B" required>
                            <span>B. <?php echo $soal['pilihan_b']; ?></span>
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label>
                            <input type="radio" name="jawaban_<?php echo $soal['id_soal']; ?>" value="C" required>
                            <span>C. <?php echo $soal['pilihan_c']; ?></span>
                        </label>
                    </div>
                    
                    <div class="option-group">
                        <label>
                            <input type="radio" name="jawaban_<?php echo $soal['id_soal']; ?>" value="D" required>
                            <span>D. <?php echo $soal['pilihan_d']; ?></span>
                        </label>
                    </div>
                </div>
                <?php 
                $no++;
                endwhile; 
                ?>

                <div class="text-center mt-20">
                    <button type="submit" class="btn btn-success" style="font-size: 1.1em; padding: 15px 50px;">
                         Submit Jawaban
                    </button>
                    <br><br>
                    <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>

    <!-- TIMER JS -->
    <script>
        // Timer 60 menit
        let timeLeft = 60 * 60; // 60 menit dalam detik
        
        function updateTimer() {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            document.getElementById('timer').textContent = 
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            
            if (timeLeft <= 300) { // 5 menit terakhir
                document.getElementById('timer').style.color = '#f56565';
            }
            
            if (timeLeft <= 0) {
                alert('Waktu habis! Jawaban Anda akan otomatis di-submit.');
                document.getElementById('testForm').submit();
            }
            
            timeLeft--;
        }
        
        // Update per detik
        setInterval(updateTimer, 1000);
        
        function confirmSubmit() {
            return confirm('Apakah Anda yakin ingin submit jawaban? Pastikan semua soal sudah terjawab!');
        }
        
        // Prevent accidental page leave
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
</body>
</html>
