<?php 
include '../layouts/header.php'; 

// --- 1. MENGHITUNG TOTAL DATA (QUERY COUNT) ---
$kategori   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori"));
$produk     = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk"));
$pelanggan  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pelanggan"));
$transaksi  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM transaksi"));
?>

<h2>Selamat datang di Dashboard</h2>

<div class="cards">
    <div class="card">
        <h2><?= $kategori; ?></h2>
        <p>Kategori</p>
    </div>
    <div class="card">
        <h2><?= $produk; ?></h2>
        <p>Produk</p>
    </div>
    <div class="card">
        <h2><?= $pelanggan; ?></h2>
        <p>Pelanggan</p>
    </div>
    <div class="card">
        <h2><?= $transaksi; ?></h2>
        <p>Transaksi</p>
    </div>
</div>

<div class="table-box">
    <h3>5 Transaksi Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total Belanja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $query = mysqli_query($koneksi, "SELECT * FROM transaksi 
                                             JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                                             ORDER BY transaksi.id_transaksi DESC 
                                             LIMIT 5");
            
            $no = 1;
            while ($data = mysqli_fetch_array($query)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                <td>
                    <b><?= htmlspecialchars($data['nama_pelanggan']); ?></b>
                </td>
                <td>Rp <?= number_format($data['total']); ?></td>
                <td>
                    <a class="btn" href="detail.php?id=<?= $data['id_transaksi']; ?>">Lihat Detail</a>
                </td>
            </tr>
            <?php } ?>

            <?php if(mysqli_num_rows($query) == 0) { ?>
                <tr>
                    <td colspan="5" align="center">Belum ada transaksi.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../layouts/footer.php'; ?>