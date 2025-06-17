<?php
include '../config/database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        header("Location: index.php");
    } else {
        echo "<p class='text-red-500'>Email atau password salah!</p>";
    }
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Login User</h1>
<form method="POST" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Login</button>
</form>
<?php include '../layouts/footer.php'; ?>