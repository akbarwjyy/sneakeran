<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakeran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-white text-2xl font-bold">Sneakeran</a>
            <div>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <a href="user/riwayat.php" class="text-white mx-2">Riwayat</a>
                    <a href="logout.php" class="text-white mx-2">Logout</a>
                <?php elseif (isset($_SESSION['id_admin'])): ?>
                    <a href="dashboard.php" class="text-white mx-2">Dashboard</a>
                    <a href="logout.php" class="text-white mx-2">Logout</a>
                <?php else: ?>
                    <a href="user/login_user.php" class="text-white mx-2">Login</a>
                    <a href="user/register_user.php" class="text-white mx-2">Register</a>
                    <a href="admin/login_admin.php" class="text-white mx-2">Admin</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6">