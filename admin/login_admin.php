<?php
// Memulai session untuk mengelola data login admin
session_start();

// Mengimpor file konfigurasi database untuk koneksi MySQL
include '../config/database.php';

// REDIRECT PROTECTION: Jika admin sudah login, langsung ke dashboard
// Mencegah admin yang sudah login mengakses halaman login lagi
if (isset($_SESSION['id_admin'])) {
    header("Location: dashboard.php"); // Redirect ke dashboard admin
    exit(); // Menghentikan eksekusi script
}

// INISIALISASI VARIABEL: Untuk menyimpan pesan error
$error = '';

// PROSES LOGIN: Cek apakah form login di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form login
    $username = $_POST['username']; // Username yang diinput admin
    $password = $_POST['password']; // Password yang diinput admin

    // QUERY DATABASE: Cari admin berdasarkan username menggunakan prepared statement
    // Prepared statement mencegah SQL injection
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameter: "s" = string untuk username
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Eksekusi query
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    // VALIDASI LOGIN: Cek apakah admin ditemukan dan password cocok
    if ($admin && password_verify($password, $admin['password'])) {
        // LOGIN BERHASIL: Set session data admin
        $_SESSION['id_admin'] = $admin['id_admin']; // ID admin untuk identifikasi
        $_SESSION['username'] = $admin['username']; // Username untuk tampilan

        // Redirect ke dashboard setelah login berhasil
        header("Location: dashboard.php");
        exit();
    } else {
        // LOGIN GAGAL: Set pesan error
        $error = "Username atau password salah!";
    }
}
?>

<?php
// Mengimpor template header yang berisi navigasi dan CSS
include '../layouts/header.php';
?>

<!-- LAYOUT UTAMA: Full height dengan flexbox untuk centering -->
<div class="min-h-screen flex flex-col">

    <!-- CONTAINER LOGIN: Centered di tengah layar -->
    <div class="flex-grow flex items-center justify-center bg-gray-50 py-8">

        <!-- CARD LOGIN: Form login dengan styling modern -->
        <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-lg shadow-md">

            <!-- HEADER FORM: Judul dan deskripsi -->
            <div>
                <h2 class="text-center text-2xl font-bold text-gray-900">
                    Login Admin
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Silakan masuk ke dashboard admin
                </p>
            </div>

            <!-- NOTIFIKASI ERROR: Tampilkan jika login gagal -->
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <!-- FORM LOGIN: Method POST untuk keamanan -->
            <form method="POST" class="space-y-4">
                <div class="space-y-4">

                    <!-- INPUT USERNAME -->
                    <div>
                        <!-- Label untuk username -->
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>

                        <!-- Container input dengan icon -->
                        <div class="relative">
                            <!-- Icon user di sebelah kiri input -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>

                            <!-- Input field username -->
                            <input id="username" name="username" type="text" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md
                                       placeholder-gray-500 text-gray-900
                                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                       focus:z-10 sm:text-sm">
                        </div>
                    </div>

                    <!-- INPUT PASSWORD -->
                    <div>
                        <!-- Label untuk password -->
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>

                        <!-- Container input dengan icon -->
                        <div class="relative">
                            <!-- Icon lock di sebelah kiri input -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>

                            <!-- Input field password (type="password" untuk menyembunyikan teks) -->
                            <input id="password" name="password" type="password" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md
                                       placeholder-gray-500 text-gray-900
                                       focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                       focus:z-10 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="pt-2">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent
                               text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition duration-150 ease-in-out">

                        <!-- Icon login di sebelah kiri tombol -->
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>

                        <!-- Teks tombol -->
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Mengimpor template footer
    include '../layouts/footer.php';
    ?>
</div>