<?php
include '../config/database.php';
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $target = "../assets/img/" . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    $query = "INSERT INTO barang (nama_barang, deskripsi, harga, stok, gambar) VALUES ('$nama_barang', '$deskripsi', '$harga', '$stok', '$gambar')";
    mysqli_query($conn, $query);
    header("Location: barang_lihat.php");
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Tambah Sepatu</h1>
<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Nama Sepatu</label>
        <input type="text" name="nama_barang" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" class="w-full p-2 border rounded"></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Harga</label>
        <input type="number" name="harga" class="w-full p-2 border rounded" step="0.01" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Stok</label>
        <input type="number" name="stok" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Gambar</label>
        <input type="file" name="gambar" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Tambah</button>
</form>
<?php include '../layouts/footer.php'; ?>