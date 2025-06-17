<?php
include '../config/database.php';
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
}
$query = "SELECT * FROM barang";
$result = mysqli_query($conn, $query);
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Daftar Sepatu</h1>
<table class="w-full bg-white rounded shadow-md">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Nama</th>
            <th class="p-2">Harga</th>
            <th class="p-2">Stok</th>
            <th class="p-2">Gambar</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="p-2"><?php echo $row['nama_barang']; ?></td>
                <td class="p-2"><?php echo $row['harga']; ?></td>
                <td class="p-2"><?php echo $row['stok']; ?></td>
                <td class="p-2"><img src="../assets/img/<?php echo $row['gambar']; ?>" width="50"></td>
                <td class="p-2">
                    <a href="barang_edit.php?id=<?php echo $row['id_barang']; ?>" class="text-blue-600">Edit</a>
                    <a href="barang_hapus.php?id=<?php echo $row['id_barang']; ?>" class="text-red-600">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../layouts/footer.php'; ?>