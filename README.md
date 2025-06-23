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

## 🛠️ Teknologi yang Digunakan

- **Backend:**
  - PHP 7.4+
  - MySQL 5.7+
  - Session Management
  - Secure Authentication
- **Frontend:**
  - HTML5
  - CSS3 (Tailwind CSS)
  - JavaScript ES6+
  - AJAX untuk interaksi dinamis
- **UI/UX:**
  - Font Awesome Icons
  - Custom Animations
  - Responsive Images
  - Modern Color Schemes
- **Server:** Laragon/Apache
- **Tools:**
  - Git untuk version control
  - VS Code dengan extensions
  - MySQL Workbench

## 📁 Struktur Project

```
sneakeran/
├── admin/                 # Area admin
│   ├── login_admin.php    # Login admin
│   ├── dashboard.php      # Dashboard admin
│   ├── barang_tambah.php  # Tambah barang
│   ├── barang_edit.php    # Edit barang
│   ├── barang_hapus.php   # Hapus barang
│   └── barang_lihat.php   # Lihat barang
│   └── barang_list.php    # List barang
│   └── logout.php         # Logout user
├── assets/                # Asset statis
│   ├── css/              # Stylesheet
│   ├── img/              # Images
├── config/
│   └── database.php      # Konfigurasi database
├── layouts/
│   ├── header.php        # Header template
│   └── footer.php        # Footer template
├── user/                 # Area user
|   ├── checkout.php      # Chechkout barang
│   ├── register_user.php # Registrasi user
│   ├── login_user.php    # Login user
│   ├── logout.php        # Logout user
|   ├── riwayat.php       # Riwayat pembelian barang
│   └── index.php         # Landing page
|── db.sql                # Schema database
└── README.md             # Informasi project
```

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
   CREATE DATABASE sneakeran;

   # Import schema
   mysql -u username -p sneakeran < db.sql
   ```

3. **Konfigurasi Aplikasi**

   - Sesuaikan `config/database.php`:
     ```php
     $host = 'localhost';
     $username = 'your_username';
     $password = 'your_password';
     $database = 'sneakeran';
     ```

4. **Akses Aplikasi**
   - Admin: `http://localhost/sneakeran/admin/login.php`
   - User: `http://localhost/sneakeran/user/login.php`

## 🔐 Keamanan

- **Database:**
  - Prepared statements untuk prevent SQL injection
  - Parameter binding untuk query
  - Input sanitization
- **Authentication:**
  - Password hashing dengan bcrypt
  - Session management yang aman
  - Remember-me functionality dengan secure tokens
- **Forms:**
  - CSRF protection pada semua form
  - Validasi input client & server side
  - File upload validation
- **General:**
  - XSS prevention
  - Error handling yang aman
  - Secure headers
  - Rate limiting untuk login attempts

## 🎨 UI/UX Features

- **Design Responsif:**
  - Mobile-first approach
  - Breakpoints yang optimal
  - Flexbox dan Grid layout
- **Interaktivitas:**
  - Smooth scrolling
  - Hover effects
  - Loading states
  - Animasi transisi
- **User Experience:**
  - Intuitive navigation
  - Clear error messages
  - Success feedback
  - Loading indicators
  - Form validation hints

## 🔄 Workflow Development

1. **Setup Local:**
   - Clone repo
   - Install dependencies
   - Setup database
2. **Development:**
   - Create feature branch
   - Implement changes
   - Test thoroughly
3. **Testing:**
   - Unit testing
   - Integration testing
   - UI/UX testing
4. **Deployment:**
   - Merge to main
   - Deploy to staging
   - Final testing
   - Deploy to production

## 📝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/NewFeature`)
3. Commit changes (`git commit -am 'Add NewFeature'`)
4. Push to branch (`git push origin feature/NewFeature`)
5. Create Pull Request
