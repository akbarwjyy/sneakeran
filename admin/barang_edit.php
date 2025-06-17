<?php
include '../config/database.php';
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
}
$id_barang = $_GET['id'];
$query = "SELECT * FROM barang WHERE id_barang = $id_barang";
$result = mysqli_query($conn, $query);
$barang = mysqli_fetch_assoc($result);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'] ? $_FILES['gambar']['name'] : $barang['gambar'];
    if ($_FILES['gambar']['name']) {
        $target = "../assets/img/" . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    }
    $query = "UPDATE barang SET nama_barang='$nama_barang', deskripsi='$deskripsi', harga='$harga', stok='$stok', gambar='$gambar' WHERE id_barang=$id_barang";
    mysqli_query($conn, $query);
    header("Location: barang_lihat.php");
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Edit Sepatu</h1>
<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Nama Sepatu</label>
        <input type="text" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" class="w-full p-2 border rounded"><?php echo $barang['deskripsi']; ?></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Harga</label>
        <input type="number" name="harga" value="<?php echo $barang['harga']; ?>" class="w-full p-2 border rounded" step="0.01" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Stok</label>
        <input type="number" name="stok" value="<?php echo $barang['stok']; ?>" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Gambar</label>
        <input type="file" name="gambar" class="w-full p-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Update</button>
</form>
<?php include '../layouts/footer.php'; ?>