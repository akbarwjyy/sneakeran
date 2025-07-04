# Sneakeran - Sistem Manajemen Toko Sepatu

Sneakeran adalah aplikasi web manajemen toko sepatu yang dibangun menggunakan PHP dan MySQL. Aplikasi ini menyediakan antarmuka yang modern dan responsif untuk pengelolaan inventaris sepatu, dengan fitur terpisah untuk admin dan pengguna.

## 🚀 Fitur

### Fitur Admin

- Login/logout sistem dengan autentikasi aman
- Dashboard admin dengan ringkasan data dan statistik
- CRUD (Create, Read, Update, Delete) barang dengan validasi
- Manajemen stok dan harga sepatu
- Interface modern, responsif dan user-friendly
- Feedback visual untuk setiap aksi
- Preview gambar saat upload
- Validasi form real-time

### Fitur User

- Registrasi dan login pengguna dengan validasi
- Landing page modern dengan:
  - Hero section animatif
  - Gradient dan efek visual modern
  - Katalog sepatu interaktif
  - Badge status stok real-time
  - Koleksi unggulan dengan efek hover
  - Testimoni pelanggan
  - Newsletter subscription
- Fitur pencarian dan filter produk
- Efek interaktif dan animasi pada semua elemen
- Responsive design untuk semua device

## ⚙️ Instalasi

1. **Persiapan Server**

   ```bash
   # Clone repository
   git clone https://github.com/username/sneakeran.git

   # Set permission
   chmod 755 -R sneakeran/
   chmod 777 -R sneakeran/assets/uploads/
   ```

2. **Setup Database**

   ```sql
   # Create database
   CREATE DATABASE db_sneakeran;

   # Import schema
   mysql -u username -p db_sneakeran < db.sql
   ```

3. **Konfigurasi Aplikasi**

   - Sesuaikan `config/database.php`:
     ```php
     $host = 'localhost';
     $username = 'root';
     $password = '';
     $database = 'db_sneakeran';
     ```

4. **Akses Aplikasi**
   - Admin: `http://localhost/sneakeran/admin/login_admin.php`
   - User: `http://localhost/sneakeran/user/index.php`
