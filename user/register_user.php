<?php
// Memulai session untuk mengakses data session yang ada
session_start();
// Mengimpor file konfigurasi database untuk koneksi MySQL
include '../config/database.php';

// REDIRECT PROTECTION: Jika user sudah login, langsung ke halaman index
if (isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}

// INISIALISASI VARIABEL: Untuk menyimpan pesan error
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Tambahkan validasi dasar untuk mencegah input kosong
    if (empty($nama) || empty($email) || empty($_POST['password'])) {
        $_SESSION['error'] = "Semua kolom harus diisi!";
        header("Location: register_user.php");
        exit();
    }

    // Periksa apakah email sudah terdaftar 
    $check_query = "SELECT id_user FROM users WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    // Jika ada hasil, berarti email sudah terdaftar
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = "Email ini sudah terdaftar. Silakan gunakan email lain atau login.";
        header("Location: register_user.php");
        exit();
    }
    // Tutup prepared statement untuk pengecekan email
    mysqli_stmt_close($stmt_check);

    // Menggunakan prepared statement untuk INSERT agar lebih aman
    $query = "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Cek apakah query berhasil disiapkan
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $password);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Registrasi berhasil! Silakan login.";
            header("Location: login_user.php");
            exit();
            // Jika eksekusi berhasil, redirect ke halaman login
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat registrasi: " . mysqli_error($conn);
        }
        // Tutup prepared statement setelah eksekusi
        mysqli_stmt_close($stmt);
        // Jika eksekusi gagal, set pesan error
    } else {
        $_SESSION['error'] = "Gagal menyiapkan query registrasi: " . mysqli_error($conn);
    }
}
?>
<!-- Mengimpor template header yang berisi navigasi dan CSS -->
<?php include '../layouts/header.php'; ?>

<div class="min-h-screen flex flex-col">
    <div class="flex-grow flex items-center justify-center bg-gray-50 py-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-lg shadow-md">
            <div>
                <h2 class="text-center text-2xl font-bold text-gray-900">
                    Registrasi Pengguna
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Daftar akun baru Anda
                </p>
            </div>
            <!-- NOTIFIKASI ERROR: Menampilkan pesan error jika ada -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <!-- NOTIFIKASI PESAN: Menampilkan pesan sukses jika ada -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['message']; ?></span>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="nama" name="nama" type="text" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md text-gray-900
                                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                       focus:z-10 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md text-gray-900
                                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                       focus:z-10 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md text-gray-900
                                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                       focus:z-10 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent
                               text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Daftar
                    </button>
                </div>

                <div class="text-sm text-center">
                    Sudah punya akun? <a href="login_user.php" class="font-medium text-indigo-600 hover:text-indigo-500">Login di sini</a>
                </div>
            </form>
        </div>
    </div>
    <!-- Mengimpor template footer yang berisi script dan penutup HTML -->
    <?php include '../layouts/footer.php'; ?>
</div>