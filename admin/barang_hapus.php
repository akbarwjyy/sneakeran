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

    // --- PERBAIKAN DI SINI ---
    // Ambil informasi gambar sebelum menghapus
    // Ubah dari 'sepatu' menjadi 'barang' dan 'id_sepatu' menjadi 'id_barang'
    $query_select_gambar = "SELECT gambar FROM barang WHERE id_barang = ?";
    $stmt_select_gambar = mysqli_prepare($conn, $query_select_gambar);

    if (!$stmt_select_gambar) {
        $_SESSION['error'] = "Gagal menyiapkan query select gambar: " . mysqli_error($conn);
        header("Location: barang_list.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt_select_gambar, "i", $id);
    mysqli_stmt_execute($stmt_select_gambar);
    $result_gambar = mysqli_stmt_get_result($stmt_select_gambar);
    $barang = mysqli_fetch_assoc($result_gambar); // Ubah $sepatu menjadi $barang

    // Hapus data dari database
    // Ubah dari 'sepatu' menjadi 'barang' dan 'id_sepatu' menjadi 'id_barang'
    $query_delete = "DELETE FROM barang WHERE id_barang = ?";
    $stmt_delete = mysqli_prepare($conn, $query_delete);

    if (!$stmt_delete) {
        $_SESSION['error'] = "Gagal menyiapkan query delete: " . mysqli_error($conn);
        header("Location: barang_list.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt_delete, "i", $id);

    if (mysqli_stmt_execute($stmt_delete)) {
        // Hapus file gambar jika ada
        // Gunakan $barang['gambar'] dan pastikan file_exists
        if ($barang && $barang['gambar'] && file_exists("../assets/img/" . $barang['gambar'])) {
            unlink("../assets/img/" . $barang['gambar']);
        }
        $_SESSION['message'] = "Barang berhasil dihapus!"; // Ubah pesan sukses
    } else {
        $_SESSION['error'] = "Gagal menghapus barang: " . mysqli_error($conn); // Ubah pesan error
    }

    mysqli_stmt_close($stmt_select_gambar);
    mysqli_stmt_close($stmt_delete);
} else {
    $_SESSION['error'] = "ID barang tidak valid!"; // Ubah pesan error
}

header("Location: barang_list.php");
exit();
