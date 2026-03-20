<!-- HTML -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mahasiswa - Universitas Nusantara</title>
    <!-- Font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Css Link -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- FIRST PAGE -->

    <!-- HEADER FIRST PAGE = university logo, email, location, phone. -->
    <div class="container">
        <div class="header">
            <div class="header-logo"><img src="user/University Logo.png" alt="" width="300"></div>
            <h1 style="color: white">UNIVERSITAS NUSANTARA</h1>
            <p style="color: white;">Jl. Cipinang Indah No. 13, Indonesia</p>
            <p style="color: white;">Email: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="d6bfb8b0b996a3b8bfa0b3a4a5bfa2b7a5b8a3a5b7b8a2b7a4b7f8b7b5f8bfb2">universitasnusantara@gmail.com</a> | Telp: (021) 1234567</p>
        </div>

        <!-- SECOND HEADER FIRST PAGE -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title text-center" style="color:white">Selamat Datang di Portal Penerimaan Mahasiswa Baru</h2>
            </div>
            
            <!-- Login calon mahasiswa -->
            <div class="grid-2">
                <div class="card" style="background: linear-gradient(135deg, #3ebdd096 0%, #3ebdd096 100%);">
                    <h3 style="color: white; margin-bottom: 15px;"><i class="fa-solid fa-user"></i> Login Calon Mahasiswa</h3>
                    <p style="color: white; margin-bottom: 20px;">Akses untuk calon mahasiswa baru yang ingin mendaftar, mengikuti test, dan melihat hasil seleksi</p>
                    <a href="user/login.php" class="btn btn-success btn-block" style="color: white;">Masuk sebagai Calon Mahasiswa</a>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="user/register.php" style="color: white; text-decoration: underline;">Belum punya akun? Daftar di sini</a>
                    </div>
                </div>

                <!-- Login administrator -->
                <div class="card" style="background: linear-gradient(135deg, #3ebdd096 0%, #3ebdd096 100%);">
                    <h3 style="color: white; margin-bottom: 15px;"><i class="fa-brands fa-black-tie"></i> Login Administrator</h3>
                    <p style="color: white; margin-bottom: 20px;">Akses khusus untuk admin untuk mengelola data calon mahasiswa, soal test, dan pengumuman</p>
                    <a href="admin/login.php" class="btn btn-warning btn-block">Masuk sebagai Admin</a>
                </div>
            </div>

            <!-- Informasi pendaftaran -->
            <div class="card mt-20" style="background: #f8eed1;">
                <h3 style="color: #2d3748; margin-bottom: 15px;"><i class="fa-solid fa-list-check"></i> Informasi Pendaftaran</h3>
                <ul style="color: #4a5568; line-height: 2; margin-left: 20px;">
                    <li>Pastikan Anda telah membuat akun terlebih dahulu</li>
                    <li>Lengkapi data diri dengan benar</li>
                    <li>Ikuti test online yang telah disediakan</li>
                    <li>Lihat pengumuman hasil test</li>
                    <li>Lakukan daftar ulang jika dinyatakan lulus</li>
                    <li>Dapatkan NIM (Nomor Induk Mahasiswa) Anda</li>
                </ul>
            </div>

            <!-- PEMBERITAHUAN -->
            <div class="card mt-20" style="background: #fffbeb; border-left: 4px solid #f59e0b;">
                <h3 style="color: #78350f; margin-bottom: 10px;"><i class="fa-solid fa-triangle-exclamation"></i> Penting!</h3>
                <p style="color: #78350f;">Simpan username dan password Anda dengan baik. Nomor test akan diberikan setelah Anda login untuk pertama kali.</p>
            </div>
        </div>

       <!-- FOOTER -->
        <div class="footer" align="center">
            <p style="color: white;">&copy; 2026 Universitas Nusantara. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
