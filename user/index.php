<?php
include '../config/database.php';
$query = "SELECT * FROM barang";
$result = mysqli_query($conn, $query);
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Daftar Sepatu</h1>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white p-4 rounded shadow-md">
            <img src="assets/img/<?php echo $row['gambar']; ?>" class="w-full h-48 object-cover rounded">
            <h2 class="text-xl font-bold mt-2"><?php echo $row['nama_barang']; ?></h2>
            <p class="text-gray-600"><?php echo $row['deskripsi']; ?></p>
            <p class="text-blue-600 font-bold">Rp <?php echo number_format($row['harga'], 2); ?></p>
            <p>Stok: <?php echo $row['stok']; ?></p>
            <?php if (isset($_SESSION['id_user'])): ?>
                <a href="checkout.php?id=<?php echo $row['id_barang']; ?>" class="bg-blue-600 text-white p-2 rounded mt-2 inline-block">Beli Sekarang</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
<?php include '../layouts/footer.php'; ?>