<?php
// Pastikan session sudah dimulai jika belum (jika ini halaman utama user dashboard)
// Jika ini adalah halaman yang terpisah dan tidak selalu memerlukan login,
// pastikan $_SESSION['id_user'] diatur di halaman login user.
session_start();

include '../config/database.php';

$query = "SELECT * FROM barang";
$result = mysqli_query($conn, $query);

// Tambahkan pengecekan error untuk query
if (!$result) {
    // Tangani error, misalnya log error atau tampilkan pesan ramah pengguna
    die("Error mengambil data barang: " . mysqli_error($conn));
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Daftar Sepatu</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white p-4 rounded shadow-md">
                    <img src="../assets/img/<?php echo htmlspecialchars($row['gambar']); ?>"
                        alt="<?php echo htmlspecialchars($row['nama_barang']); ?>"
                        class="w-full h-48 object-cover rounded">

                    <h2 class="text-xl font-bold mt-2"><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                    <p class="text-gray-600"><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>
                    <p class="text-blue-600 font-bold">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo htmlspecialchars($row['stok']); ?></p>

                    <?php if (isset($_SESSION['id_user'])): ?>
                        <a href="checkout.php?id=<?php echo htmlspecialchars($row['id_barang']); ?>" class="bg-blue-600 text-white p-2 rounded mt-2 inline-block">Beli Sekarang</a>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 mt-2">Login untuk membeli.</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full text-center text-gray-500">
                Belum ada barang yang tersedia.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>