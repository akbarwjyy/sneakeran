<?php
include '../config/database.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
}
$id_user = $_SESSION['id_user'];
$query = "SELECT t.*, b.nama_barang FROM transaksi t JOIN barang b ON t.id_barang = b.id_barang WHERE t.id_user = $id_user";
$result = mysqli_query($conn, $query);
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Riwayat Pembelian</h1>
<table class="w-full bg-white rounded shadow-md">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Nama Sepatu</th>
            <th class="p-2">Jumlah</th>
            <th class="p-2">Total Harga</th>
            <th class="p-2">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="p-2"><?php echo $row['nama_barang']; ?></td>
                <td class="p-2"><?php echo $row['jumlah']; ?></td>
                <td class="p-2">Rp <?php echo number_format($row['total_harga'], 2); ?></td>
                <td class="p-2"><?php echo $row['tanggal_transaksi']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../layouts/footer.php'; ?>