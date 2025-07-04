<?php
// Mengaktifkan session untuk mengakses data session yang ada
session_start();

// Menghapus semua data session yang tersimpan
// Ini akan menghapus $_SESSION['id_admin'], $_SESSION['username'], dan semua data session lainnya
session_destroy();

// Mengarahkan user kembali ke halaman login admin
header("Location: login_admin.php");
