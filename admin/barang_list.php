<?php
// Memulai session untuk mengelola data login admin
session_start();

// Mengimpor file konfigurasi database untuk koneksi MySQL
include '../config/database.php';

// PROTEKSI AKSES: Memastikan hanya admin yang bisa mengakses halaman ini
// Jika session 'id_admin' tidak ada, redirect ke halaman login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Mengimpor template header yang berisi navigasi dan styling CSS
include '../layouts/header.php';
?>

<!-- CONTAINER UTAMA dengan styling responsif menggunakan Tailwind CSS -->
<div class="container mx-auto px-4 py-8">

    <!-- HEADER SECTION: Judul halaman dan tombol aksi -->
    <div class="flex justify-between items-center mb-6">
        <!-- Judul halaman -->
        <h1 class="text-2xl font-bold">Daftar Barang</h1>

        <!-- Tombol untuk menambah barang baru -->
        <a href="barang_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Tambah Barang
        </a>
    </div>

    <?php
    // SISTEM NOTIFIKASI: Menampilkan pesan feedback dari operasi sebelumnya

    // Tampilkan pesan sukses jika ada (misal: berhasil tambah/edit/hapus barang)
    if (isset($_SESSION['message'])) {
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']); // Hapus pesan dari session setelah ditampilkan
    }

    // Tampilkan pesan error jika ada
    if (isset($_SESSION['error'])) {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']); // Hapus pesan dari session setelah ditampilkan
    }
    ?>

    <!-- CONTAINER TABEL dengan styling modern dan shadow -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Wrapper untuk membuat tabel responsive (scroll horizontal di mobile) -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">

                <!-- HEADER TABEL dengan styling abu-abu -->
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Kolom untuk gambar produk -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <!-- Kolom nama barang -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
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

                <!-- BODY TABEL dengan garis pemisah antar baris -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // QUERY DATABASE: Mengambil semua data barang dari tabel 'barang'
                    // ORDER BY id_barang DESC = mengurutkan dari yang terbaru ditambahkan
                    $query = "SELECT * FROM barang ORDER BY id_barang DESC";
                    $result = mysqli_query($conn, $query);

                    // ERROR HANDLING: Cek apakah query berhasil dieksekusi
                    if (!$result) {
                        // Jika query gagal, tampilkan pesan error dengan detail kesalahan
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center text-red-500'>Error: " . mysqli_error($conn) . "</td></tr>";
                    }
                    // Cek apakah ada data yang ditemukan
                    else if (mysqli_num_rows($result) == 0) {
                        // Jika tidak ada data barang, tampilkan pesan kosong
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center'>Belum ada data barang</td></tr>";
                    }
                    // Jika ada data, tampilkan dalam bentuk baris tabel
                    else {
                        // LOOP DATA: Iterasi setiap baris data barang
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Mulai baris tabel dengan hover effect
                            echo "<tr class='hover:bg-gray-50'>";

                            // KOLOM GAMBAR
                            echo "<td class='px-6 py-4 whitespace-nowrap'>";
                            // Cek apakah gambar ada dan file fisiknya exist di server
                            if ($row['gambar'] && file_exists("../assets/img/" . $row['gambar'])) {
                                // Tampilkan gambar dengan XSS protection menggunakan htmlspecialchars
                                echo "<img src='../assets/img/" . htmlspecialchars($row['gambar']) . "' 
                                             alt='" . htmlspecialchars($row['nama_barang']) . "' 
                                             class='w-20 h-20 object-cover rounded'>";
                            } else {
                                // Jika gambar tidak ada, tampilkan placeholder
                                echo "<div class='w-20 h-20 bg-gray-200 rounded flex items-center justify-center'>
                                             <span class='text-gray-500'>No Image</span>
                                             </div>";
                            }
                            echo "</td>";

                            // KOLOM NAMA BARANG dengan XSS protection
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['nama_barang']) . "</td>";

                            // KOLOM HARGA dengan format Rupiah Indonesia
                            // number_format() untuk memformat angka dengan pemisah ribuan
                            echo "<td class='px-6 py-4 whitespace-nowrap'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";

                            // KOLOM STOK dengan XSS protection
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['stok']) . "</td>";

                            // KOLOM DESKRIPSI dengan truncate untuk membatasi panjang teks
                            echo "<td class='px-6 py-4'><div class='truncate max-w-xs'>" . htmlspecialchars($row['deskripsi']) . "</div></td>";

                            // KOLOM AKSI: Tombol Edit dan Hapus
                            echo "<td class='px-6 py-4 whitespace-nowrap'>";

                            // Tombol Edit - mengarah ke halaman barang_edit.php dengan parameter ID
                            echo "<a href='barang_edit.php?id=" . $row['id_barang'] . "' class='text-blue-600 hover:text-blue-900 mr-3'>Edit</a>";

                            // Tombol Hapus dengan konfirmasi JavaScript
                            // onclick return confirm() akan menampilkan dialog konfirmasi
                            echo "<a href='barang_hapus.php?id=" . $row['id_barang'] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Apakah Anda yakin ingin menghapus sepatu ini?\")'>Hapus</a>";

                            echo "</td>";
                            echo "</tr>"; // Tutup baris tabel
                        } // End while loop
                    } // End else (ada data)
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