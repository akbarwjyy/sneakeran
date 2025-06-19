<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan base URL berdasarkan jenis user yang login
$baseUrl = 'index.php';
$logoutUrl = 'logout.php';
if (isset($_SESSION['id_admin'])) {
    // Jika yang login adalah admin
    if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
        $baseUrl = 'dashboard.php';
        $logoutUrl = 'logout.php';
    } else {
        $baseUrl = 'admin/dashboard.php';
        $logoutUrl = 'admin/logout.php';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakeran - Admin Dashboard</title>
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
                <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="user/riwayat.php"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-history mr-2"></i>Riwayat
                    </a>
                    <a href="logout.php"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                <?php elseif (isset($_SESSION['id_admin'])): ?>
                    <span class="text-indigo-200 mr-4">
                        <i class="fas fa-user mr-2"></i>Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'dashboard.php' : 'admin/dashboard.php'; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="<?php echo $logoutUrl; ?>"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="user/login_user.php"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="user/register_user.php"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                    <a href="login_admin.php"
                        class="nav-link text-white hover:text-indigo-200 flex items-center">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6"> </div>