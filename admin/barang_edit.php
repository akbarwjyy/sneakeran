<?php
session_start();
include '../config/database.php';

// Cek jika admin belum login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Inisialisasi $sepatu sebagai null agar tidak terjadi undefined variable jika ID tidak ditemukan
$sepatu = null;

// Ambil data barang berdasarkan ID
if (isset($_GET['id'])) {
    $id_barang = mysqli_real_escape_string($conn, $_GET['id']); // Menggunakan id_barang

    // Gunakan nama tabel dan kolom yang benar: 'barang' dan 'id_barang'
    $query_select = "SELECT * FROM barang WHERE id_barang = ?";
    $stmt_select = mysqli_prepare($conn, $query_select);

    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_barang);
        mysqli_stmt_execute($stmt_select);
        $result_select = mysqli_stmt_get_result($stmt_select);
        $sepatu = mysqli_fetch_assoc($result_select); // Variabel tetap $sepatu untuk konsistensi di HTML

        if (!$sepatu) {
            $_SESSION['error'] = "Barang tidak ditemukan!"; // Pesan error yang lebih tepat
            header("Location: barang_list.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Gagal menyiapkan query SELECT: " . mysqli_error($conn);
        header("Location: barang_list.php");
        exit();
    }
} else {
    $_SESSION['error'] = "ID barang tidak diberikan.";
    header("Location: barang_list.php");
    exit();
}

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil ID dari hidden input atau GET parameter (kita pakai GET parameter karena sudah ada di URL)
    $id_barang_post = mysqli_real_escape_string($conn, $_POST['id_barang']); // Ambil ID dari hidden input
    // Pastikan ID dari GET dan POST cocok, atau gunakan ID dari GET saja jika lebih aman
    if ($id_barang_post != $id_barang) {
        $_SESSION['error'] = "ID barang tidak cocok!";
        header("Location: barang_list.php");
        exit();
    }

    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']); // Ubah nama_sepatu jadi nama_barang
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambar_baru = $sepatu['gambar']; // Default: gunakan gambar lama

    // Cek apakah ada file gambar baru diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar_name = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($gambar_ext, $allowed_ext)) {
            $new_gambar_filename = uniqid() . '.' . $gambar_ext;
            $upload_path = "../assets/img/";

            // Pastikan direktori ada
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            if (move_uploaded_file($gambar_tmp, $upload_path . $new_gambar_filename)) {
                // Hapus gambar lama jika ada dan berhasil upload gambar baru
                if ($sepatu['gambar'] && file_exists($upload_path . $sepatu['gambar'])) {
                    unlink($upload_path . $sepatu['gambar']);
                }
                $gambar_baru = $new_gambar_filename; // Update gambar baru
            } else {
                $_SESSION['error'] = "Gagal mengupload gambar baru!";
                header("Location: barang_edit.php?id=" . $id_barang);
                exit();
            }
        } else {
            $_SESSION['error'] = "Format file tidak didukung! Gunakan format: jpg, jpeg, png, atau webp";
            header("Location: barang_edit.php?id=" . $id_barang);
            exit();
        }
    } else if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Tangani error upload selain 'no file'
        $_SESSION['error'] = "Terjadi kesalahan saat upload gambar. Kode error: " . $_FILES['gambar']['error'];
        header("Location: barang_edit.php?id=" . $id_barang);
        exit();
    }

    // Query UPDATE
    $query_update = "UPDATE barang SET nama_barang=?, deskripsi=?, harga=?, stok=?, gambar=? WHERE id_barang=?";
    $stmt_update = mysqli_prepare($conn, $query_update);

    if ($stmt_update) {
        mysqli_stmt_bind_param($stmt_update, "ssidsi", $nama_barang, $deskripsi, $harga, $stok, $gambar_baru, $id_barang);

        if (mysqli_stmt_execute($stmt_update)) {
            $_SESSION['message'] = "Data barang berhasil diupdate!";
            header("Location: barang_list.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal mengupdate data barang: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Gagal menyiapkan query UPDATE: " . mysqli_error($conn);
    }
}

// Pastikan $sepatu ada sebelum mencoba mengaksesnya di HTML
if (!$sepatu) {
    // Ini seharusnya tidak tercapai jika logika di atas sudah benar
    $_SESSION['error'] = "Terjadi kesalahan fatal: Data barang tidak tersedia untuk diedit.";
    header("Location: barang_list.php");
    exit();
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Barang</h1>
            <a href="barang_list.php" class="text-blue-600 hover:text-blue-900">Kembali ke Daftar</a>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['message'])) { // Tambahkan ini untuk pesan sukses
            echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        }
        ?>

        <form method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="id_barang" value="<?php echo htmlspecialchars($sepatu['id_barang']); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($sepatu['nama_barang']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" class="w-full p-2 border rounded"><?php echo htmlspecialchars($sepatu['deskripsi']); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Harga</label>
                <input type="number" name="harga" value="<?php echo htmlspecialchars($sepatu['harga']); ?>" class="w-full p-2 border rounded" step="0.01" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Stok</label>
                <input type="number" name="stok" value="<?php echo htmlspecialchars($sepatu['stok']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Gambar Saat Ini</label>
                <?php if ($sepatu['gambar'] && file_exists("../assets/img/" . $sepatu['gambar'])): ?>
                    <img src="../assets/img/<?php echo htmlspecialchars($sepatu['gambar']); ?>" alt="<?php echo htmlspecialchars($sepatu['nama_barang']); ?>" class="w-32 h-32 object-cover rounded mb-2">
                <?php else: ?>
                    <p class="text-gray-500 mb-2">Tidak ada gambar saat ini.</p>
                <?php endif; ?>
                <label class="block text-gray-700 mt-2">Ganti Gambar (opsional)</label>
                <input type="file" name="gambar" class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 rounded">Update</button>
        </form>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>