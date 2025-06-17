<?php
include '../config/database.php';
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
<div class="grid grid-cols-2 gap-4">
    <a href="barang_tambah.php" class="bg-blue-600 text-white p-4 rounded text-center">Tambah Sepatu</a>
    <a href="barang_lihat.php" class="bg-blue-600 text-white p-4 rounded text-center">Lihat Sepatu</a>
</div>
<?php include '../layouts/footer.php'; ?>