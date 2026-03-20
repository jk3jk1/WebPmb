-- Database: pendaftaran_mahasiswa

CREATE DATABASE IF NOT EXISTS pendaftaran_mahasiswa;
USE pendaftaran_mahasiswa;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Program Studi
CREATE TABLE IF NOT EXISTS program_studi (
    id_prodi INT PRIMARY KEY AUTO_INCREMENT,
    kode_prodi VARCHAR(10) UNIQUE NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL,
    fakultas VARCHAR(100) DEFAULT 'Fakultas Teknologi Informasi',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
);

-- Tabel Calon Mahasiswa
CREATE TABLE IF NOT EXISTS calon_mahasiswa (
    id_calon INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_telepon VARCHAR(15),
    alamat TEXT,
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P'),
    id_prodi INT,
    nomor_test VARCHAR(20) UNIQUE,
    status_test ENUM('belum', 'sudah') DEFAULT 'belum',
    nilai_test INT DEFAULT 0,
    nilai_minimum INT DEFAULT 70,
    status_kelulusan ENUM('belum', 'lulus', 'tidak lulus') DEFAULT 'belum',
    status_daftar_ulang ENUM('belum', 'sudah') DEFAULT 'belum',
    nim VARCHAR(20) UNIQUE,
    tanggal_test DATETIME,
    tanggal_daftar_ulang DATETIME,
    foto_ktp VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi)
);

-- Tabel Soal Test
CREATE TABLE IF NOT EXISTS soal_test (
    id_soal INT PRIMARY KEY AUTO_INCREMENT,
    pertanyaan TEXT NOT NULL,
    pilihan_a VARCHAR(255) NOT NULL,
    pilihan_b VARCHAR(255) NOT NULL,
    pilihan_c VARCHAR(255) NOT NULL,
    pilihan_d VARCHAR(255) NOT NULL,
    jawaban_benar ENUM('A', 'B', 'C', 'D') NOT NULL,
    kategori VARCHAR(50) DEFAULT 'IT',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Jawaban Test
CREATE TABLE IF NOT EXISTS jawaban_test (
    id_jawaban INT PRIMARY KEY AUTO_INCREMENT,
    id_calon INT NOT NULL,
    id_soal INT NOT NULL,
    jawaban ENUM('A', 'B', 'C', 'D'),
    is_correct BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_calon) REFERENCES calon_mahasiswa(id_calon) ON DELETE CASCADE,
    FOREIGN KEY (id_soal) REFERENCES soal_test(id_soal) ON DELETE CASCADE
);

-- Insert data admin default
-- Username: admin
-- Password: admin123
INSERT INTO admin (username, password, nama_lengkap, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@universitasnusantara.ac.id');

-- CATATAN: Hash password di atas adalah hasil dari password_hash('admin123', PASSWORD_DEFAULT)
-- Jika tidak bisa login, gunakan setup_admin.php untuk membuat password yang sesuai

-- Insert data program studi IT
INSERT INTO program_studi (kode_prodi, nama_prodi, fakultas) VALUES
('TI', 'Teknik Informatika', 'Fakultas Teknologi Informasi'),
('SI', 'Sistem Informasi', 'Fakultas Teknologi Informasi'),
('TK', 'Teknik Komputer', 'Fakultas Teknologi Informasi'),
('RPL', 'Rekayasa Perangkat Lunak', 'Fakultas Teknologi Informasi'),
('DKV', 'Desain Komunikasi Visual', 'Fakultas Teknologi Informasi');

-- Insert soal test IT (20 soal)
INSERT INTO soal_test (pertanyaan, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, kategori) VALUES
('Apa kepanjangan dari HTML?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'A', 'IT'),
('Bahasa pemrograman yang berjalan di sisi client adalah?', 'PHP', 'JavaScript', 'Python', 'Java', 'B', 'IT'),
('Apa fungsi dari CSS?', 'Membuat database', 'Mengatur tampilan halaman web', 'Membuat logika program', 'Mengatur server', 'B', 'IT'),
('Database management system yang open source adalah?', 'Oracle', 'Microsoft SQL Server', 'MySQL', 'IBM DB2', 'C', 'IT'),
('Protokol yang digunakan untuk transfer file adalah?', 'HTTP', 'SMTP', 'FTP', 'DNS', 'C', 'IT'),
('Sistem operasi berbasis Linux adalah?', 'Windows', 'MacOS', 'Ubuntu', 'DOS', 'C', 'IT'),
('Bahasa pemrograman yang dikembangkan oleh Microsoft adalah?', 'Java', 'Python', 'C#', 'Ruby', 'C', 'IT'),
('Apa kepanjangan dari URL?', 'Uniform Resource Locator', 'Universal Resource Locator', 'Uniform Retail Locator', 'Universal Retail Locator', 'A', 'IT'),
('Port default untuk HTTP adalah?', '21', '22', '80', '443', 'C', 'IT'),
('Algoritma sorting yang paling efisien adalah?', 'Bubble Sort', 'Quick Sort', 'Selection Sort', 'Insertion Sort', 'B', 'IT'),
('Apa kepanjangan dari RAM?', 'Random Access Memory', 'Read Access Memory', 'Random Allocated Memory', 'Read Allocated Memory', 'A', 'IT'),
('Protocol yang menggunakan port 443 adalah?', 'HTTP', 'HTTPS', 'FTP', 'SSH', 'B', 'IT'),
('Bahasa pemrograman untuk Data Science adalah?', 'C++', 'Pascal', 'Python', 'Assembly', 'C', 'IT'),
('Apa kepanjangan dari SQL?', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', 'System Query Language', 'A', 'IT'),
('Framework PHP yang populer adalah?', 'Django', 'Laravel', 'Spring', 'Express', 'B', 'IT'),
('Topologi jaringan yang membentuk lingkaran adalah?', 'Star', 'Bus', 'Ring', 'Mesh', 'C', 'IT'),
('Apa fungsi dari firewall?', 'Mempercepat internet', 'Keamanan jaringan', 'Menyimpan data', 'Menjalankan program', 'B', 'IT'),
('Cloud computing service dari Google adalah?', 'AWS', 'Azure', 'Google Cloud Platform', 'Oracle Cloud', 'C', 'IT'),
('Version control system yang paling populer adalah?', 'SVN', 'Git', 'Mercurial', 'CVS', 'B', 'IT'),
('Apa kepanjangan dari API?', 'Application Programming Interface', 'Advanced Programming Interface', 'Application Protocol Interface', 'Advanced Protocol Interface', 'A', 'IT');
