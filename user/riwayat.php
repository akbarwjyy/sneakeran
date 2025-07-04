<?php
session_start(); // Pastikan session dimulai di awal file
// Mengimpor koneksi database
include '../config/database.php';

// Cek jika user belum login, redirect ke halaman login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Ambil riwayat transaksi menggunakan prepared statement untuk keamanan
$query = "SELECT t.*, b.nama_barang, b.gambar FROM transaksi t JOIN barang b ON t.id_barang = b.id_barang WHERE t.id_user = ? ORDER BY t.tanggal_transaksi DESC";
$stmt = mysqli_prepare($conn, $query);
// Cek apakah statement berhasil disiapkan
if (!$stmt) {
    die("Gagal menyiapkan statement riwayat: " . mysqli_error($conn));
}
// Bind parameter untuk menghindari SQL injection
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!-- Mengimpor template header yang berisi navigasi dan CSS -->
<?php include '../layouts/header.php'; ?>
<div class="min-h-screen flex flex-col bg-gray-50">
    <div class="flex-grow container mx-auto px-4 py-10 flex flex-col items-center justify-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center tracking-tight">Riwayat Pembelian</h1>
        <!-- NOTIFIKASI SYSTEM: Menampilkan pesan sukses atau error dari session -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm w-full max-w-3xl">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <!-- Jika ada pesan error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-sm w-full max-w-3xl">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-100 to-indigo-100 text-indigo-700">
                        <th class="p-3 text-left font-semibold">Gambar</th>
                        <th class="p-3 text-left font-semibold">Nama Barang</th>
                        <th class="p-3 text-center font-semibold">Jumlah</th>
                        <th class="p-3 text-right font-semibold">Total Harga</th>
                        <th class="p-3 text-left font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-b border-gray-200 hover:bg-indigo-50 transition">
                                <td class="p-3">
                                    <?php if ($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])): ?>
                                        <img src="../assets/img/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_barang']); ?>" class="w-16 h-16 object-cover rounded-lg shadow-md border border-gray-200">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-gray-800 font-medium">
                                    <?php echo htmlspecialchars($row['nama_barang']); ?>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-700 font-semibold shadow">
                                        <?php echo htmlspecialchars($row['jumlah']); ?> pcs
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <span class="font-bold text-indigo-600">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></span>
                                </td>
                                <td class="p-3">
                                    <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-mono">
                                        <?php echo date('d M Y, H:i', strtotime($row['tanggal_transaksi'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 text-lg">Belum ada riwayat pembelian.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Mengimpor template footer yang berisi script dan penutup HTML -->
<?php include '../layouts/footer.php'; ?>