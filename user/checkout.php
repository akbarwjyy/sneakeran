<?php
session_start(); // Pastikan session dimulai di awal file
include '../config/database.php';

// Cek jika user belum login, redirect ke halaman login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit();
}

// Ambil ID barang dari URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID barang tidak ditemukan.";
    header("Location: index.php"); // Redirect kembali ke daftar barang
    exit();
}
$id_barang = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data barang menggunakan prepared statement untuk keamanan
$query_barang = "SELECT * FROM barang WHERE id_barang = ?";
$stmt_barang = mysqli_prepare($conn, $query_barang);
if (!$stmt_barang) {
    die("Gagal menyiapkan statement barang: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt_barang, "i", $id_barang);
mysqli_stmt_execute($stmt_barang);
$result_barang = mysqli_stmt_get_result($stmt_barang);
$barang = mysqli_fetch_assoc($result_barang);

if (!$barang) {
    $_SESSION['error'] = "Barang tidak ditemukan.";
    header("Location: index.php");
    exit();
}

// Proses form pembelian
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0;
    $id_user = $_SESSION['id_user'];

    // Validasi jumlah
    if ($jumlah <= 0) {
        $_SESSION['error'] = "Jumlah harus lebih dari 0.";
        header("Location: checkout.php?id=" . $id_barang);
        exit();
    }
    if ($jumlah > $barang['stok']) {
        $_SESSION['error'] = "Stok tidak mencukupi. Stok tersedia: " . $barang['stok'];
        header("Location: checkout.php?id=" . $id_barang);
        exit();
    }

    $total_harga = $jumlah * $barang['harga'];
    $tanggal_transaksi = date('Y-m-d H:i:s');

    // Mulai transaksi database untuk memastikan konsistensi data
    mysqli_begin_transaction($conn);

    try {
        // Insert data transaksi menggunakan prepared statement
        $query_insert_transaksi = "INSERT INTO transaksi (id_user, id_barang, jumlah, total_harga, tanggal_transaksi) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $query_insert_transaksi);
        if (!$stmt_insert) {
            throw new Exception("Gagal menyiapkan statement insert transaksi: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_insert, "iiids", $id_user, $id_barang, $jumlah, $total_harga, $tanggal_transaksi);

        if (!mysqli_stmt_execute($stmt_insert)) {
            throw new Exception("Gagal menyimpan transaksi: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_insert);

        // Update stok barang menggunakan prepared statement
        $query_update_stok = "UPDATE barang SET stok = stok - ? WHERE id_barang = ?";
        $stmt_update = mysqli_prepare($conn, $query_update_stok);
        if (!$stmt_update) {
            throw new Exception("Gagal menyiapkan statement update stok: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_update, "ii", $jumlah, $id_barang);

        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception("Gagal mengurangi stok barang: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt_update);

        mysqli_commit($conn); // Komit transaksi jika semua berhasil
        $_SESSION['message'] = "Pembelian berhasil!";
        header("Location: riwayat.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn); // Rollback transaksi jika ada kesalahan
        $_SESSION['error'] = "Terjadi kesalahan pada transaksi: " . $e->getMessage();
        header("Location: checkout.php?id=" . $id_barang);
        exit();
    }
}
?>
<?php include '../layouts/header.php'; ?>
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Checkout</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Product Image and Details -->
                    <div class="md:w-1/2 p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                        <div class="aspect-w-16 aspect-h-12 mb-4">
                            <img src="<?php echo htmlspecialchars('../assets/img/' . $barang['gambar']); ?>"
                                alt="<?php echo htmlspecialchars($barang['nama_barang']); ?>"
                                class="object-cover w-full h-full rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300">
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($barang['nama_barang']); ?></h2>
                        <div class="space-y-2">
                            <p class="text-lg font-semibold text-indigo-600">
                                Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?>
                            </p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo $barang['stok'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Stok: <?php echo htmlspecialchars($barang['stok']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Form -->
                    <div class="md:w-1/2 p-6 border-l">
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Pembelian</h3>
                            <div class="h-px bg-gray-200 mb-4"></div>
                        </div>

                        <form method="POST" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pembelian</label>
                                <div class="relative">
                                    <input type="number"
                                        name="jumlah"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        min="1"
                                        max="<?php echo htmlspecialchars($barang['stok']); ?>"
                                        required
                                        placeholder="Masukkan jumlah">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"></div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Harga Satuan:</span>
                                    <span class="font-medium">Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?></span>
                                </div>
                                <div class="h-px bg-gray-200 my-2"></div>
                                <div class="flex justify-between items-center font-semibold text-lg">
                                    <span>Total:</span>
                                    <span id="totalPrice" class="text-indigo-600">-</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 px-6 rounded-lg font-semibold
                                           hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                                           transform hover:scale-[1.02] transition-all duration-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Konfirmasi Pembelian
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk menghitung total harga -->
    <script>
        document.querySelector('input[name="jumlah"]').addEventListener('input', function(e) {
            const jumlah = parseInt(this.value) || 0;
            const harga = <?php echo $barang['harga']; ?>;
            const total = jumlah * harga;
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        });
    </script>
</div>
<?php include '../layouts/footer.php'; ?>