<?php
session_start(); // Memulai session
session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan session
header('Location: index.php'); // Mengarahkan ke halaman landing page
exit(); // Keluar dari script
