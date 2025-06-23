<?php
session_start();
include '../config/database.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_admin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
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
        $error = "Username atau password salah!";
    }
}
?>
<?php include '../layouts/header.php'; ?>

<div class="min-h-screen flex flex-col">
    <div class="flex-grow flex items-center justify-center bg-gray-50 py-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-lg shadow-md">
            <div>
                <h2 class="text-center text-2xl font-bold text-gray-900">
                    Login Admin
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Silakan masuk ke dashboard admin
                </p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="username" name="username" type="text" required
                                class="appearance-none relative block w-full px-3 py-2 pl-2
                                       border border-gray-300 rounded-md
                                       placeholder-gray-500 text-gray-900
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
                                       border border-gray-300 rounded-md
                                       placeholder-gray-500 text-gray-900
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
                            <i class="fas fa-sign-in-alt text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../layouts/footer.php'; ?>
</div>