<?php
// Memulai session untuk mengelola data login admin
session_start();

// Mengimpor file konfigurasi database untuk koneksi MySQL
include '../config/database.php';

// PROTEKSI AKSES: Cek apakah admin sudah login
// Jika session 'id_admin' tidak ada, redirect ke halaman login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit(); // Menghentikan eksekusi script
}

// Mengimpor template header yang berisi navigasi dan CSS
include '../layouts/header.php';
?>

<!-- CONTAINER UTAMA dengan styling Tailwind CSS -->
<div class="container mx-auto px-4 py-8">

    <!-- HEADER SECTION: Judul halaman dan tombol tambah -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Barang</h1>
        <!-- Tombol untuk menambah barang baru -->
        <a href="barang_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Barang
        </a>
    </div>

    <?php
    // NOTIFIKASI SYSTEM: Menampilkan pesan sukses atau error dari session

    // Jika ada pesan sukses (misal: setelah berhasil tambah/edit/hapus)
    if (isset($_SESSION['message'])) {
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
    }

    // Jika ada pesan error
    if (isset($_SESSION['error'])) {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']); // Hapus pesan setelah ditampilkan
    }
    ?>

    <!-- TABEL CONTAINER dengan styling modern -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Wrapper untuk responsive table (scroll horizontal di mobile) -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">

                <!-- HEADER TABEL -->
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Kolom untuk gambar produk -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <!-- Kolom nama barang -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                        <!-- Kolom harga -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <!-- Kolom stok -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <!-- Kolom deskripsi -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <!-- Kolom aksi (edit/hapus) -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY TABEL -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // QUERY DATABASE: Mengambil semua data barang
                    // ORDER BY id_barang DESC = urutkan dari yang terbaru
                    $query = "SELECT * FROM barang ORDER BY id_barang DESC";
                    $result = mysqli_query($conn, $query);

                    // ERROR HANDLING: Cek apakah query berhasil
                    if (!$result) {
                        // Jika query gagal, tampilkan pesan error
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center text-red-500'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else {
                        // Cek apakah ada data barang
                        if (mysqli_num_rows($result) == 0) {
                            // Jika tidak ada data, tampilkan pesan kosong
                            echo "<tr><td colspan='6' class='px-6 py-4 text-center'>Belum ada data barang</td></tr>";
                        } else {
                            // LOOP DATA: Tampilkan setiap barang dalam baris tabel
                            while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                                <!-- BARIS DATA dengan hover effect -->
                                <tr class="hover:bg-gray-50">

                                    <!-- KOLOM GAMBAR -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        // Cek apakah gambar ada dan file fisiknya exist
                                        if ($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])):
                                        ?>
                                            <!-- Tampilkan gambar jika ada -->
                                            <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>"
                                                alt="<?= htmlspecialchars($row['nama_barang']) ?>"
                                                class="w-20 h-20 object-cover rounded">
                                        <?php else: ?>
                                            <!-- Placeholder jika gambar tidak ada -->
                                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-500">No Image</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- KOLOM NAMA BARANG dengan XSS protection -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['nama_barang']) ?>
                                    </td>

                                    <!-- KOLOM HARGA dengan format Rupiah -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                    </td>

                                    <!-- KOLOM STOK -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['stok']) ?>
                                    </td>

                                    <!-- KOLOM DESKRIPSI dengan truncate untuk teks panjang -->
                                    <td class="px-6 py-4">
                                        <div class="truncate max-w-xs">
                                            <?= htmlspecialchars($row['deskripsi']) ?>
                                        </div>
                                    </td>

                                    <!-- KOLOM AKSI: Tombol Edit dan Hapus -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <!-- Tombol Edit -->
                                            <a href="barang_edit.php?id=<?= $row['id_barang'] ?>"
                                                class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>

                                            <!-- Tombol Hapus dengan konfirmasi JavaScript -->
                                            <a href="barang_hapus.php?id=<?= $row['id_barang'] ?>"
                                                class="text-red-600 hover:text-red-900 transition duration-200"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                    <?php
                            } // End while loop
                        } // End else (ada data)
                    } // End else (query berhasil)
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Mengimpor template footer
include '../layouts/footer.php';
?>