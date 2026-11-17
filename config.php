<?php
/**
 * Pengaturan aplikasi (bukan kredensial database).
 * Kredensial database ada di config/database.php.
 */
return [
    'app_name'               => 'Peminjaman Alat',
    'timezone'               => 'Asia/Jakarta',

    // true hanya saat pengembangan; di hosting biarkan false agar pesan error tidak bocor.
    'debug'                  => false,

    // Denda keterlambatan per hari (Rupiah). 0 = denda tidak dihitung otomatis.
    'denda_per_hari'         => 0,

    // Alat dianggap "stok menipis" bila jumlah tersedia <= angka ini.
    'stok_menipis'           => 2,

    // Jumlah baris per halaman pada daftar data.
    'per_page'               => 15,

    // Sesi otomatis berakhir bila tidak ada aktivitas selama (menit).
    'session_idle_minutes'   => 120,

    // Password bawaan akun admin dari file SQL. Bila login memakai password ini,
    // pengguna wajib menggantinya sebelum memakai fitur lain.
    'default_admin_password' => 'Admin@2026',
];
