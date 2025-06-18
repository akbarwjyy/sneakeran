<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Ambil data sepatu berdasarkan ID
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM sepatu WHERE id_sepatu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $sepatu = mysqli_fetch_assoc($result);

    if (!$sepatu) {
        $_SESSION['error'] = "Sepatu tidak ditemukan!";
        header("Location: barang_list.php");
        exit();
    }
} else {
    header("Location: barang_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sepatu = mysqli_real_escape_string($conn, $_POST['nama_sepatu']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Cek apakah ada file gambar baru
    if ($_FILES['gambar']['size'] > 0) {
        $gambar = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($gambar_ext, $allowed_ext)) {
            $new_gambar = uniqid() . '.' . $gambar_ext;
            move_uploaded_file($gambar_tmp, "../assets/img/" . $new_gambar);

            // Hapus gambar lama jika ada
            if ($sepatu['gambar'] && file_exists("../assets/img/" . $sepatu['gambar'])) {
                unlink("../assets/img/" . $sepatu['gambar']);
            }

            $query = "UPDATE sepatu SET nama_sepatu=?, harga=?, stok=?, deskripsi=?, gambar=? WHERE id_sepatu=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "siissi", $nama_sepatu, $harga, $stok, $deskripsi, $new_gambar, $id);
        } else {
            $_SESSION['error'] = "Format file tidak didukung!";
            header("Location: barang_edit.php?id=" . $id);
            exit();
        }
    } else {
        $query = "UPDATE sepatu SET nama_sepatu=?, harga=?, stok=?, deskripsi=? WHERE id_sepatu=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sissi", $nama_sepatu, $harga, $stok, $deskripsi, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Data sepatu berhasil diupdate!";
        header("Location: barang_list.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal mengupdate data sepatu!";
    }
}
$nama_barang = $_POST['nama_barang'];
$deskripsi = $_POST['deskripsi'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$gambar = $_FILES['gambar']['name'] ? $_FILES['gambar']['name'] : $barang['gambar'];
if ($_FILES['gambar']['name']) {
    $target = "../assets/img/" . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
}
$query = "UPDATE barang SET nama_barang='$nama_barang', deskripsi='$deskripsi', harga='$harga', stok='$stok', gambar='$gambar' WHERE id_barang=$id_barang";
mysqli_query($conn, $query);
header("Location: barang_lihat.php");
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Edit Sepatu</h1>
<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Nama Sepatu</label>
        <input type="text" name="nama_barang" value="<?php echo $barang['nama_barang']; ?>" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" class="w-full p-2 border rounded"><?php echo $barang['deskripsi']; ?></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Harga</label>
        <input type="number" name="harga" value="<?php echo $barang['harga']; ?>" class="w-full p-2 border rounded" step="0.01" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Stok</label>
        <input type="number" name="stok" value="<?php echo $barang['stok']; ?>" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Gambar</label>
        <input type="file" name="gambar" class="w-full p-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Update</button>
</form>
<?php include '../layouts/footer.php'; ?>