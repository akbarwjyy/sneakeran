<?php
include '../config/database.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
}
$id_barang = $_GET['id'];
$query = "SELECT * FROM barang WHERE id_barang = $id_barang";
$result = mysqli_query($conn, $query);
$barang = mysqli_fetch_assoc($result);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $total_harga = $jumlah * $barang['harga'];
    $tanggal_transaksi = date('Y-m-d H:i:s');
    $id_user = $_SESSION['id_user'];
    $query = "INSERT INTO transaksi (id_user, id_barang, jumlah, total_harga, tanggal_transaksi) VALUES ('$id_user', '$id_barang', '$jumlah', '$total_harga', '$tanggal_transaksi')";
    mysqli_query($conn, $query);
    $query = "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = $id_barang";
    mysqli_query($conn, $query);
    header("Location: riwayat.php");
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Checkout</h1>
<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold"><?php echo $barang['nama_barang']; ?></h2>
    <p>Harga: Rp <?php echo number_format($barang['harga'], 2); ?></p>
    <p>Stok: <?php echo $barang['stok']; ?></p>
    <form method="POST" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Jumlah</label>
            <input type="number" name="jumlah" class="w-full p-2 border rounded" min="1" max="<?php echo $barang['stok']; ?>" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white p-2 rounded">Konfirmasi Pembelian</button>
    </form>
</div>
<?php include '../layouts/footer.php'; ?>