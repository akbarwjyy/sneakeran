<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}
?>
<?php include '../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="barang_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-md text-center transition duration-300">
            <i class="fas fa-plus-circle text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Tambah Barang</div>
        </a>
        <a href="barang_list.php" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-md text-center transition duration-300">
            <i class="fas fa-list text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Kelola Barang</div>
        </a>
        <a href="orders.php" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-md text-center transition duration-300">
            <i class="fas fa-shopping-cart text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Pesanan</div>
        </a>
    </div>

    <!-- Tabel Sepatu Terbaru -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Sepatu Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $query = "SELECT * FROM barang ORDER BY id_barang DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "<tr><td colspan='4' class='px-6 py-4 text-center text-red-500'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else {
                        if (mysqli_num_rows($result) == 0) {
                            echo "<tr><td colspan='4' class='px-6 py-4 text-center'>Belum ada data sepatu</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['nama_barang']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['stok']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                echo "<a href='barang_edit.php?id=" . $row['id_barang'] . "' class='text-blue-600 hover:text-blue-900 mr-3'>Edit</a>";
                                echo "<a href='barang_hapus.php?id=" . $row['id_barang'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>