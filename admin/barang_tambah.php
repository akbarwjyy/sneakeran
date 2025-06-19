<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Inisialisasi variabel gambar sebelum digunakan
$gambar = null; // Atur ke null pada awalnya

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi dan ambil nilai dari form
    $nama_barang = isset($_POST['nama_barang']) ? mysqli_real_escape_string($conn, $_POST['nama_barang']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, $_POST['deskripsi']) : '';
    $harga = isset($_POST['harga']) ? mysqli_real_escape_string($conn, $_POST['harga']) : 0;
    $stok = isset($_POST['stok']) ? mysqli_real_escape_string($conn, $_POST['stok']) : 0;
    $error = false;

    // Validasi data
    if (empty($nama_barang)) {
        $error = true;
        $_SESSION['error'] = "Nama barang harus diisi!";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = true;
        $_SESSION['error'] = "Harga harus berupa angka dan lebih dari 0!";
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = true;
        $_SESSION['error'] = "Stok harus berupa angka dan tidak boleh negatif!";
    }

    // Upload gambar
    // Periksa apakah file diunggah dan tidak ada error
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_name = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($gambar_ext, $allowed_ext)) {
            $new_gambar = uniqid() . '.' . $gambar_ext;
            $upload_path = "../assets/img/";

            // Buat direktori jika belum ada
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            if (move_uploaded_file($gambar_tmp, $upload_path . $new_gambar)) {
                // File berhasil diunggah, tetapkan new_gambar ke $gambar
                $gambar = $new_gambar;
            } else {
                $error = true;
                $_SESSION['error'] = "Gagal mengupload gambar!";
            }
        } else {
            $error = true;
            $_SESSION['error'] = "Format file tidak didukung! Gunakan format: jpg, jpeg, png, atau webp";
        }
    } else if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Tangani kesalahan upload lainnya selain tidak ada file yang dipilih
        $error = true;
        $_SESSION['error'] = "Terjadi kesalahan saat upload gambar. Kode error: " . $_FILES['gambar']['error'];
    }
    // Jika tidak ada file yang diunggah (UPLOAD_ERR_NO_FILE), $gambar tetap null, yang memang diinginkan.

    // Insert ke database jika tidak ada error
    if (!$error) {
        $query = "INSERT INTO barang (nama_barang, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        // Pastikan 's' untuk tipe string untuk $gambar, bahkan jika itu null
        mysqli_stmt_bind_param($stmt, "ssids", $nama_barang, $deskripsi, $harga, $stok, $gambar);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Barang berhasil ditambahkan!";
            header("Location: barang_list.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menambahkan barang: " . mysqli_error($conn);
        }
    }
}
?>

<?php include '../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Tambah Barang Baru</h1>
            <a href="barang_list.php" class="text-blue-600 hover:text-blue-900">Kembali ke Daftar</a>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700">Nama Sepatu</label>
                <input type="text" name="nama_barang" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" class="w-full p-2 border rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Harga</label>
                <input type="number" name="harga" class="w-full p-2 border rounded" step="0.01" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Stok</label>
                <input type="number" name="stok" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Gambar</label>
                <input type="file" name="gambar" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 rounded">Tambah</button>
        </form>
        <?php include '../layouts/footer.php'; ?>