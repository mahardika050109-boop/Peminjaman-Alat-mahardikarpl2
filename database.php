<?php
  
$server   = '127.0.0.1';// alamat database; di PC sendiri pakai localhost
$username = 'root';              // user default XAMPP/Laragon
$pass     = 'root';                  // password default XAMPP/Laragon masih kosong
$database = 'peminjaman_alat.sql';  // nama database (sesuai hasil import pengaduan_sarpas.sql)

// Buka koneksi ke MySQL
$conn = mysqli_connect($server, $username, $pass, $database);

// Kalau gagal konek, program dihentikan dan pesan errornya ditampilkan
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Atur encoding ke utf8mb4 supaya huruf khusus dan emoji tidak rusak
mysqli_set_charset($conn, 'utf8mb4');