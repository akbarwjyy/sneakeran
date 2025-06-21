<?php
// Pastikan session sudah dimulai, jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan base URL secara dinamis berdasarkan lokasi file PHP saat ini
$current_path = $_SERVER['PHP_SELF'];
$is_admin_area = strpos($current_path, '/admin/') !== false;
$is_user_area = strpos($current_path, '/user/') !== false;

$baseUrl = ''; // Default empty, akan diisi di bawah
$logoutUrl = ''; // Default empty, akan diisi di bawah

if ($is_admin_area) {
    // Jika berada di direktori admin
    $baseUrl = 'dashboard.php'; // Mengarahkan ke dashboard.php di direktori yang sama
    $logoutUrl = 'logout.php'; // Mengarahkan ke logout.php di direktori yang sama
} else if ($is_user_area) {
    // Jika berada di direktori user
    $baseUrl = 'index.php'; // Mengarahkan ke index.php di direktori yang sama (user/index.php)
    $logoutUrl = 'logout.php'; // Mengarahkan ke logout.php di direktori yang sama (user/logout.php)
} else {
    // Jika berada di root (seperti index.php utama)
    $baseUrl = 'user/index.php'; // Mengarahkan ke user/index.php
    $logoutUrl = 'user/logout.php'; // Mengarahkan ke user/logout.php (jika ada logout di root)
}

// Override logoutUrl jika admin yang login (sudah di handle di atas, tapi ini untuk memastikan konsistensi)
if (isset($_SESSION['id_admin']) && !$is_admin_area) {
    $logoutUrl = 'admin/logout.php'; // Jika admin login tapi ada di halaman non-admin
} elseif (isset($_SESSION['id_user']) && !$is_user_area) {
    $logoutUrl = 'user/logout.php'; // Jika user login tapi ada di halaman non-user
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakeran - <?php echo $is_admin_area ? 'Admin Dashboard' : 'Pusat Sepatu'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-indigo-600 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo $baseUrl; ?>"
                class="text-white text-2xl font-bold hover:text-indigo-200 transition duration-300">
                <i class="fas fa-shoe-prints mr-2"></i>Sneakeran
            </a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['id_user'])): /* */ ?>
                    <a href="<?php echo $is_user_area ? 'riwayat.php' : 'user/riwayat.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-history mr-2"></i>Riwayat
                    </a>
                    <a href="<?php echo $logoutUrl; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                <?php elseif (isset($_SESSION['id_admin'])): /* */ ?>
                    <span class="text-indigo-200 mr-4">
                        <i class="fas fa-user mr-2"></i>Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="<?php echo $is_admin_area ? 'dashboard.php' : 'admin/dashboard.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="<?php echo $logoutUrl; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                <?php else: /* */ ?>
                    <a href="<?php echo $is_user_area ? 'login_user.php' : 'user/login_user.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="<?php echo $is_user_area ? 'register_user.php' : 'user/register_user.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                    <!-- <a href="<?php echo $is_admin_area ? 'login_admin.php' : 'admin/login_admin.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a> -->
                <?php endif; /* */ ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6"> </div>
</body>

</html>