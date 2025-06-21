<?php
session_start(); // Pastikan session dimulai di awal file
include '../config/database.php';

// Cek jika user belum login, redirect ke halaman login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil riwayat transaksi menggunakan prepared statement untuk keamanan
$query = "SELECT t.*, b.nama_barang, b.gambar FROM transaksi t JOIN barang b ON t.id_barang = b.id_barang WHERE t.id_user = ? ORDER BY t.tanggal_transaksi DESC";
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Gagal menyiapkan statement riwayat: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<?php include '../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Riwayat Pembelian</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow-md overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Gambar</th>
                    <th class="p-2 text-left">Nama Barang</th>
                    <th class="p-2 text-center">Jumlah</th>
                    <th class="p-2 text-right">Total Harga</th>
                    <th class="p-2 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="p-2">
                                <?php if ($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])): ?>
                                    <img src="../assets/img/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_barang']); ?>" class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td class="p-2"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($row['jumlah']); ?></td>
                            <td class="p-2 text-right">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($row['tanggal_transaksi']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada riwayat pembelian.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>