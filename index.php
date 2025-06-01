<?php
include  'config.php';
session_start();

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Proses tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['cart'][$menu_id] += $quantity;
    } else {
        $_SESSION['cart'][$menu_id] = $quantity;
    }
}

// Ambil data kantin
$query_kantin = "SELECT * FROM kantin";
$result_kantin = mysqli_query($conn, $query_kantin);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .about-section {
            background-color: #f8f9fa;
        }

        .about-section img {
            transition: transform 0.3s ease;
        }

        .about-section img:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Kantin Sekolah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#about">About Kantin</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Cafetaria List</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-to-buy">How to Buy</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- About Kantin -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">About Kantin</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="https://i.ytimg.com/vi/3AWQnv6g9sk/maxresdefault.jpg"
                        class="img-fluid rounded shadow-lg"
                        alt="Kantin">
                </div>
                <div class="col-md-6">
                    <p>Kantin sekolah kami menyediakan berbagai menu makanan dan minuman yang lezat dan sehat untuk para siswa.</p>
                </div>
            </div>

        </div>
        </div>
    </section>

    <!-- Vidio -->
    <div class="container d-flex justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/3AWQnv6g9sk?si=L35fxmTgj-IIs4_H" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

    </div>
    <!-- vidio -->
    <!-- Menu List -->
    <section id="menu" class="py-5 bg-light">
        <div class="container" >
            <h2 class="text-center mb-4">Cafetaria List</h2>
            <?php while ($kantin = mysqli_fetch_assoc($result_kantin)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3><?php echo $kantin['nama_kantin']; ?></h3>
                        <p><?php echo $kantin['deskripsi']; ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $query_menu = "SELECT * FROM menu WHERE kantin_id = " . $kantin['id'];
                            $result_menu = mysqli_query($conn, $query_menu);
                            while ($menu = mysqli_fetch_assoc($result_menu)):
                            ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="<?php echo $menu['foto_menu']; ?>" class="card-img-top" alt="<?php echo $menu['nama_menu']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $menu['nama_menu']; ?></h5>
                                            <p class="card-text">Rp <?php echo number_format($menu['harga']); ?></p>
                                            <p class="card-text">Stok: <?php echo $menu['stok']; ?></p>
                                            <form method="POST">
                                                <input type="hidden" name="menu_id" value="<?php echo $menu['id']; ?>">
                                                <div class="mb-2">
                                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $menu['stok']; ?>" class="form-control">
                                                </div>
                                                <button type="submit" name="add_to_cart" class="btn btn-primary">Tambah ke Keranjang</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- How to Buy -->
    <section id="how-to-buy" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">How to Buy</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h3>Keranjang Belanja</h3>
                            <?php if (empty($_SESSION['cart'])): ?>
                                <p>Keranjang belanja kosong</p>
                            <?php else: ?>
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
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="checkout.php" class="btn btn-success">Checkout</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="process_contact.php" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p>&copy; 2024 Kantin Sekolah. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>