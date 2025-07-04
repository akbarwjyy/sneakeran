<?php
// Memulai session untuk mengelola data login admin
session_start();

// Mengimpor file konfigurasi database untuk koneksi MySQL
include '../config/database.php';

// PROTEKSI AKSES: Memastikan hanya admin yang sudah login yang bisa mengakses dashboard
// Jika session 'id_admin' tidak ada, berarti admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php"); // Redirect ke halaman login admin
    exit(); // Menghentikan eksekusi script setelah redirect
}
?>

<?php
// Mengimpor template header yang berisi navigasi, CSS, dan meta tags
include '../layouts/header.php';
?>

<!-- CONTAINER UTAMA dengan styling responsif menggunakan Tailwind CSS -->
<div class="container mx-auto px-4 py-8">

    <!-- JUDUL DASHBOARD -->
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

    <!-- MENU NAVIGASI UTAMA: Grid 2 kolom untuk desktop, 1 kolom untuk mobile -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- CARD MENU: Tambah Barang -->
        <!-- Link yang berfungsi sebagai tombol besar dengan hover effect -->
        <a href="barang_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-md text-center transition duration-300">
            <!-- Icon Font Awesome untuk tambah -->
            <i class="fas fa-plus-circle text-2xl mb-2"></i>
            <!-- Teks menu -->
            <div class="text-lg font-semibold">Tambah Barang</div>
        </a>

        <!-- CARD MENU: Kelola Barang -->
        <!-- Link menuju halaman daftar/kelola barang -->
        <a href="barang_lihat.php" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-md text-center transition duration-300">
            <!-- Icon Font Awesome untuk list -->
            <i class="fas fa-list text-2xl mb-2"></i>
            <!-- Teks menu -->
            <div class="text-lg font-semibold">Kelola Barang</div>
        </a>
    </div>

    <!-- SECTION PREVIEW: Tabel Barang Terbaru -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Judul section -->
        <h2 class="text-xl font-semibold mb-4">Barang Terbaru</h2>

        <!-- Wrapper untuk tabel responsif (scroll horizontal di mobile) -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">

                <!-- HEADER TABEL dengan styling abu-abu -->
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Kolom nama barang -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <!-- Kolom harga -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <!-- Kolom stok -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <!-- Kolom aksi (edit/hapus) -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY TABEL dengan garis pemisah antar baris -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // QUERY DATABASE: Mengambil 5 barang terbaru untuk preview di dashboard
                    // ORDER BY id_barang DESC = urutkan dari yang terbaru
                    // LIMIT 5 = batasi hanya 5 data untuk preview
                    $query = "SELECT * FROM barang ORDER BY id_barang DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);

                    // ERROR HANDLING: Cek apakah query berhasil dieksekusi
                    if (!$result) {
                        // Jika query gagal, tampilkan pesan error dengan detail kesalahan
                        echo "<tr><td colspan='4' class='px-6 py-4 text-center text-red-500'>Error: " . mysqli_error($conn) . "</td></tr>";
                    } else {
                        // Cek apakah ada data yang ditemukan
                        if (mysqli_num_rows($result) == 0) {
                            // Jika tidak ada data barang, tampilkan pesan kosong
                            echo "<tr><td colspan='4' class='px-6 py-4 text-center'>Belum ada data barang</td></tr>";
                        } else {
                            // LOOP DATA: Iterasi setiap baris data barang (maksimal 5)
                            while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                                <!-- BARIS DATA dengan hover effect -->
                                <tr class="hover:bg-gray-50">

                                    <!-- KOLOM NAMA BARANG dengan XSS protection -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['nama_barang']) ?>
                                    </td>

                                    <!-- KOLOM HARGA dengan format Rupiah Indonesia -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                    </td>

                                    <!-- KOLOM STOK dengan XSS protection -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['stok']) ?>
                                    </td>

                                    <!-- KOLOM AKSI: Tombol Edit dan Hapus -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-3">

                                            <!-- Tombol Edit dengan icon -->
                                            <!-- Mengarah ke barang_edit.php dengan parameter ID barang -->
                                            <a href="barang_edit.php?id=<?= $row['id_barang'] ?>"
                                                class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>

                                            <!-- Tombol Hapus dengan konfirmasi JavaScript -->
                                            <!-- onclick return confirm() menampilkan dialog konfirmasi -->
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