<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}

include '../layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Sepatu</h1>
        <a href="barang_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Sepatu
        </a>
    </div>

    <?php
    // Handle success/error messages
    if (isset($_SESSION['message'])) {
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $query = "SELECT * FROM sepatu ORDER BY id_sepatu DESC";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>";
                        echo "<img src='../assets/img/" . htmlspecialchars($row['gambar']) . "' alt='" . htmlspecialchars($row['nama_sepatu']) . "' class='w-20 h-20 object-cover rounded'>";
                        echo "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['nama_sepatu']) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['stok']) . "</td>";
                        echo "<td class='px-6 py-4'><div class='truncate max-w-xs'>" . htmlspecialchars($row['deskripsi']) . "</div></td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>";
                        echo "<a href='barang_edit.php?id=" . $row['id_sepatu'] . "' class='text-blue-600 hover:text-blue-900 mr-3'>Edit</a>";
                        echo "<a href='barang_hapus.php?id=" . $row['id_sepatu'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Apakah Anda yakin ingin menghapus sepatu ini?\")'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>