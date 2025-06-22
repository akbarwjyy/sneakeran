<?php
session_start();
include '../config/database.php';

$is_logged_in = isset($_SESSION['id_user']);

if ($is_logged_in) {
    $query = "SELECT * FROM barang";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error mengambil data barang: " . mysqli_error($conn));
    }
}
?>

<?php include '../layouts/header.php'; ?>

<div class="container mx-auto px-4">
    <?php if ($is_logged_in): ?>
        <!-- Konten untuk user yang sudah login -->
        <h1 class="text-2xl font-bold mb-4">Daftar Sepatu</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white p-4 rounded shadow-md">
                        <img src="../assets/img/<?php echo htmlspecialchars($row['gambar']); ?>"
                            alt="<?php echo htmlspecialchars($row['nama_barang']); ?>"
                            class="w-full h-48 object-cover rounded">

                        <h2 class="text-xl font-bold mt-2"><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                        <p class="text-gray-600"><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>
                        <p class="text-indigo-600 font-bold">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <p>Stok: <?php echo htmlspecialchars($row['stok']); ?></p>

                        <a href="checkout.php?id=<?php echo htmlspecialchars($row['id_barang']); ?>"
                            class="bg-indigo-600 text-white p-2 rounded mt-2 inline-block hover:bg-indigo-700 transition">
                            Beli Sekarang
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-gray-500">
                    Belum ada barang yang tersedia.
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-indigo-600 to-indigo-900 text-white py-20 rounded-3xl overflow-hidden mb-12">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute transform rotate-45 -translate-y-1/2 -translate-x-1/2" style="width: 1000px; height: 1000px; background: repeating-linear-gradient(45deg, #ffffff 0, #ffffff 10px, transparent 10px, transparent 20px);"></div>
            </div>

            <div class="relative container mx-auto px-6 flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-1/2 text-center lg:text-left mb-10 lg:mb-0">
                    <h1 class="text-4xl lg:text-5xl font-extrabold mb-6 leading-tight">
                        Temukan Gaya Sempurna di Setiap Langkah
                    </h1>
                    <p class="text-xl mb-8 text-indigo-100">
                        Koleksi sneakers eksklusif untuk gaya hidup modern Anda. Kualitas premium, desain trendy, dan kenyamanan tak tertandingi.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="login_user.php"
                            class="bg-white text-indigo-600 hover:bg-indigo-50 px-8 py-4 rounded-xl text-lg font-semibold transition duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login Sekarang
                        </a>
                        <a href="register_user.php"
                            class="border-2 border-white text-white hover:bg-white hover:text-indigo-600 px-8 py-4 rounded-xl text-lg font-semibold transition duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                        </a>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <img src="../assets/img/hero-sneaker.png" alt="Sneaker Collection"
                        class="mx-auto transform hover:rotate-12 transition-transform duration-500"
                        style="max-width: 500px;">
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-fire"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Koleksi Terbaru</h3>
                <p class="text-gray-600">Sneakers terbaru dari brand ternama dengan update reguler.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">100% Original</h3>
                <p class="text-gray-600">Jaminan keaslian produk dengan sertifikat resmi.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Layanan pengiriman express ke seluruh Indonesia.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Layanan 24/7</h3>
                <p class="text-gray-600">Dukungan pelanggan responsif setiap saat.</p>
            </div>
        </div> <!-- Featured Products Preview -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-3xl font-bold text-center mb-8">Koleksi Unggulan</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="group relative overflow-hidden rounded-lg bg-gray-50 p-4 transition duration-300 border border-transparent hover:border-indigo-500 hover:shadow-2xl hover:scale-105 cursor-pointer">
                    <img src="../assets/img/sneaker1.png" alt="Featured Sneaker 1" class="w-full h-64 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-black">
                            <h3 class="font-bold">Limited Edition</h3>
                            <p>Login untuk melihat</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gray-50 p-4 transition duration-300 border border-transparent hover:border-indigo-500 hover:shadow-2xl hover:scale-105 cursor-pointer">
                    <img src="../assets/img/sneaker1.png" alt="Featured Sneaker 1" class="w-full h-64 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-black">
                            <h3 class="font-bold">Limited Edition</h3>
                            <p>Login untuk melihat</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gray-50 p-4 transition duration-300 border border-transparent hover:border-indigo-500 hover:shadow-2xl hover:scale-105 cursor-pointer">
                    <img src="../assets/img/sneaker1.png" alt="Featured Sneaker 1" class="w-full h-64 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-black">
                            <h3 class="font-bold">Limited Edition</h3>
                            <p>Login untuk melihat</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gray-50 p-4 transition duration-300 border border-transparent hover:border-indigo-500 hover:shadow-2xl hover:scale-105 cursor-pointer">
                    <img src="../assets/img/sneaker1.png" alt="Featured Sneaker 1" class="w-full h-64 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-black">
                            <h3 class="font-bold">Limited Edition</h3>
                            <p>Login untuk melihat</p>
                        </div>
                    </div>
                </div>
                <div class="group relative overflow-hidden rounded-lg bg-gray-50 p-4 transition duration-300 border border-transparent hover:border-indigo-500 hover:shadow-2xl hover:scale-105 cursor-pointer">
                    <img src="../assets/img/sneaker1.png" alt="Featured Sneaker 1" class="w-full h-64 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 text-black">
                            <h3 class="font-bold">Limited Edition</h3>
                            <p>Login untuk melihat</p>
                        </div>
                    </div>
                </div>

                <!-- Repeat for other featured products -->
                <!-- You'll need to add actual product images in these sections -->
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-8 text-white text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Dapatkan Update Terbaru</h2>
            <p class="mb-6">Daftar sekarang dan dapatkan informasi tentang koleksi terbaru dan penawaran eksklusif!</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="register_user.php"
                    class="border-2 border-indigo-600 bg-white text-indigo-600 hover:bg-indigo-50 px-8 py-3 rounded-lg font-semibold transition duration-300 hover:bg-indigo-600 hover:text-white">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../layouts/footer.php'; ?>