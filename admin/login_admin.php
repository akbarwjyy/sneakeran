<?php
session_start();
include '../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_admin'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Username atau password salah!</p>";
    }
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Login Admin</h1>
<form method="POST" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Username</label>
        <input type="text" name="username" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Login</button>
</form>
<?php include '../layouts/footer.php'; ?>