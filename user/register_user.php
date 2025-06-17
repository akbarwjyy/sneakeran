<?php
include '../config/database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
    mysqli_query($conn, $query);
    header("Location: login_user.php");
}
?>
<?php include '../layouts/header.php'; ?>
<h1 class="text-2xl font-bold mb-4">Registrasi User</h1>
<form method="POST" class="bg-white p-6 rounded shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="nama" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white p-2 rounded">Daftar</button>
</form>
<?php include '../layouts/footer.php'; ?>