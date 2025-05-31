# Aplikasi Pemesanan Makanan Kantin Sekolah

## Deskripsi
Aplikasi ini adalah sistem pemesanan makanan online untuk kantin sekolah yang memungkinkan siswa untuk memesan makanan dan minuman secara digital.

## Struktur Database
Database menggunakan MySQL dengan nama `db_kantin` yang terdiri dari beberapa tabel:

1. `kantin` - Menyimpan data kantin
   - id (Primary Key)
   - nama_kantin
   - foto_kantin
   - deskripsi

2. `menu` - Menyimpan data menu makanan/minuman
   - id (Primary Key)
   - kantin_id (Foreign Key)
   - nama_menu
   - harga
   - stok
   - foto_menu

3. `pesanan` - Menyimpan data pesanan
   - id (Primary Key)
   - nama_pelanggan
   - email
   - total_harga
   - tanggal_pesanan

4. `detail_pesanan` - Menyimpan detail item pesanan
   - id (Primary Key)
   - pesanan_id (Foreign Key)
   - menu_id (Foreign Key)
   - quantity

## Alur Kerja Aplikasi

### 1. Halaman Utama (index.php)
- Menampilkan navigasi dengan menu: About Kantin, Cafetaria List, How to Buy, Contact Us
- Bagian About Kantin menampilkan informasi dan gambar kantin
- Bagian Cafetaria List menampilkan daftar kantin beserta menu-menu mereka
- Setiap menu memiliki tombol untuk menambahkan ke keranjang
- Bagian How to Buy menampilkan keranjang belanja dengan total harga
- Bagian Contact Us berisi form untuk mengirim pesan/kritik

### 2. Proses Pemesanan
1. User memilih menu dan jumlah yang diinginkan
2. Menu ditambahkan ke keranjang (disimpan dalam session)
3. User dapat melihat ringkasan pesanan di keranjang
4. User melakukan checkout dengan mengisi nama dan email
5. Sistem memproses pesanan:
   - Menyimpan data pesanan ke database
   - Mengurangi stok menu yang dipesan
   - Menghasilkan QR Code untuk pengambilan pesanan

### 3. Halaman Checkout (checkout.php)
- Menampilkan form untuk data pelanggan
- Menampilkan ringkasan pesanan dan total harga
- Setelah pembayaran berhasil, menampilkan QR Code
- QR Code digunakan untuk pengambilan pesanan di kantin

## Penjelasan Kode

### 1. Koneksi Database (config.php)
```php
$host = "localhost";
$username = "root";
$password = "";
$database = "db_kantin";

$conn = mysqli_connect($host, $username, $password, $database);
```
File ini menangani koneksi ke database MySQL.

### 2. Manajemen Keranjang (index.php)
```php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];
    
    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['cart'][$menu_id] += $quantity;
    } else {
        $_SESSION['cart'][$menu_id] = $quantity;
    }
}
```
Kode ini mengelola keranjang belanja menggunakan session PHP.

### 3. Proses Checkout (checkout.php)
```php
if (isset($_POST['checkout'])) {
    // Hitung total
    foreach ($_SESSION['cart'] as $menu_id => $quantity) {
        $query = "SELECT harga FROM menu WHERE id = $menu_id";
        $result = mysqli_query($conn, $query);
        $menu = mysqli_fetch_assoc($result);
        $total += $menu['harga'] * $quantity;
    }
    
    // Simpan pesanan
    $query = "INSERT INTO pesanan (nama_pelanggan, email, total_harga) VALUES ('$nama', '$email', $total)";
    mysqli_query($conn, $query);
    
    // Update stok
    foreach ($_SESSION['cart'] as $menu_id => $quantity) {
        $query = "UPDATE menu SET stok = stok - $quantity WHERE id = $menu_id";
        mysqli_query($conn, $query);
    }
}
```
Kode ini menangani proses checkout, termasuk:
- Perhitungan total harga
- Penyimpanan data pesanan
- Update stok menu

## Cara Menjalankan Aplikasi

1. Import database:
   - Buat database baru dengan nama `db_kantin`
   - Import file `database.sql`

2. Konfigurasi:
   - Sesuaikan pengaturan database di `config.php`
   - Pastikan web server (Apache) dan MySQL berjalan

3. Akses aplikasi:
   - Buka browser
   - Akses `http://localhost/nama_folder`

## Fitur Keamanan
- Validasi input form
- Sanitasi data sebelum disimpan ke database
- Pengecekan stok sebelum pemesanan
- Session management untuk keranjang belanja

## Teknologi yang Digunakan
- PHP 7.4+
- MySQL
- Bootstrap 5
- HTML5
- CSS3
- JavaScript 