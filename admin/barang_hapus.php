<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Ambil informasi gambar sebelum menghapus
    $query = "SELECT gambar FROM sepatu WHERE id_sepatu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $sepatu = mysqli_fetch_assoc($result);

    // Hapus data dari database
    $query = "DELETE FROM sepatu WHERE id_sepatu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Hapus file gambar jika ada
        if ($sepatu['gambar'] && file_exists("../assets/img/" . $sepatu['gambar'])) {
            unlink("../assets/img/" . $sepatu['gambar']);
        }
        $_SESSION['message'] = "Sepatu berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus sepatu!";
    }
} else {
    $_SESSION['error'] = "ID sepatu tidak valid!";
}

header("Location: barang_list.php");
exit();
