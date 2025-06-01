<?php
require_once 'config.php';
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Proses checkout
if (isset($_POST['checkout'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $total = 0;

    // Hitung total
    foreach ($_SESSION['cart'] as $menu_id => $quantity) {
        $query = "SELECT harga FROM menu WHERE id = $menu_id";
        $result = mysqli_query($conn, $query);
        $menu = mysqli_fetch_assoc($result);
        $total += $menu['harga'] * $quantity;
    }

    // Insert ke tabel pesanan
    $query = "INSERT INTO pesanan (nama_pelanggan, email, total_harga) VALUES ('$nama', '$email', $total)";
    mysqli_query($conn, $query);
    $pesanan_id = mysqli_insert_id($conn);

    // Insert ke tabel detail_pesanan dan update stok
    foreach ($_SESSION['cart'] as $menu_id => $quantity) {
        $query = "INSERT INTO detail_pesanan (pesanan_id, menu_id, quantity) VALUES ($pesanan_id, $menu_id, $quantity)";
        mysqli_query($conn, $query);

        // Update stok
        $query = "UPDATE menu SET stok = stok - $quantity WHERE id = $menu_id";
        mysqli_query($conn, $query);
    }

    // Clear cart
    $_SESSION['cart'] = array();

    // Redirect ke halaman sukses
    header("Location: checkout.php?success=1&id=$pesanan_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kantin Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <?php if (isset($_GET['success'])): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
            <script>
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Silahkan tunjukkan QR Code ini ke kasir untuk mengambil pesanan Anda.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan QR Code setelah alert ditutup
                        document.getElementById('success-content').style.display = 'block';
                    }
                });
            </script>
            <div id="success-content" style="display: none;">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 class="mb-4">Pesanan Berhasil!</h2>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo $_GET['id']; ?>"
                                    alt="QR Code" class="mb-4">
                                <h4>ID Pesanan: <?php echo $_GET['id']; ?></h4>
                                <p>Silahkan tunjukkan QR Code ini ke kasir untuk mengambil pesanan Anda.</p>
                                <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="text-center mb-4">Checkout</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-4">
                                    <h4>Ringkasan Pesanan</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            foreach ($_SESSION['cart'] as $menu_id => $quantity):
                                                $query = "SELECT * FROM menu WHERE id = $menu_id";
                                                $result = mysqli_query($conn, $query);
                                                $menu = mysqli_fetch_assoc($result);
                                                $subtotal = $menu['harga'] * $quantity;
                                                $total += $subtotal;
                                            ?>
                                                <tr>
                                                    <td><?php echo $menu['nama_menu']; ?></td>
                                                    <td><?php echo $quantity; ?></td>
                                                    <td>Rp <?php echo number_format($menu['harga']); ?></td>
                                                    <td>Rp <?php echo number_format($subtotal); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                <td>Rp <?php echo number_format($total); ?></td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="container d-flex justify-content-center align-items-center">
                                                <img src="https://png.pngtree.com/png-clipart/20220729/original/pngtree-qr-code-png-image_8438558.png" alt="No" width="500">

                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <button type="submit" name="checkout" class="btn btn-success w-100">Bayar Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>