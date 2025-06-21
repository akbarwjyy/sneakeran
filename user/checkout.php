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
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-bold"><?php echo htmlspecialchars($barang['nama_barang']); ?></h2>
        <p>Harga: Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?></p>
        <p>Stok Tersedia: <?php echo htmlspecialchars($barang['stok']); ?></p>
        <form method="POST" class="mt-4">
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Beli</label>
                <input type="number" name="jumlah" class="w-full p-2 border rounded" min="1" max="<?php echo htmlspecialchars($barang['stok']); ?>" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 rounded">Konfirmasi Pembelian</button>
        </form>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>