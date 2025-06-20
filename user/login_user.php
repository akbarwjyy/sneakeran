<?php
session_start();
include '../config/database.php';

if (isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = "Email atau password salah!";
    }
}
?>

<?php include '../layouts/header.php'; ?>

<div class="min-h-screen flex flex-col">
    <div class="flex-grow flex items-center justify-center bg-gray-50 py-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-lg shadow-md">
            <div>
                <h2 class="text-center text-2xl font-bold text-gray-900">
                    Login User
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Silakan masuk ke akun Anda
                </p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['message']; ?></span>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="appearance-none relative block w-full px-3 py-2 pl-10
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
                            class="appearance-none relative block w-full px-3 py-2 pl-10
                                   border border-gray-300 rounded-md text-gray-900
                                   focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
                                   focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent
                               text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Masuk
                    </button>
                </div>

                <div class="text-sm text-center">
                    Belum punya akun? <a href="register_user.php" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar di sini</a>
                </div>
            </form>
        </div>
    </div>

    <?php include '../layouts/footer.php'; ?>
</div>