<?php 
include '../layouts/header.php'; 

// 1. AMBIL ID DARI URL
$id_transaksi = $_GET['id'];

// 2. AMBIL DATA HEADER TRANSAKSI (Join dengan Pelanggan)
$query_header = mysqli_query($koneksi, "SELECT * FROM transaksi 
                                        JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                                        WHERE transaksi.id_transaksi = '$id_transaksi'");
$data_header = mysqli_fetch_array($query_header);

// Cek jika data tidak ditemukan (misal user iseng ganti ID di URL)
if (!$data_header) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit;
}
?>

<h2>Detail Transaksi</h2>

<div class="table-box" style="margin-bottom: 20px;">
    <h3>Info Transaksi</h3>
    <table style="width: 100%;">
        <tr>
            <td width="200">No. Transaksi</td>
            <td>: #<?= $data_header['id_transaksi']; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= date('d F Y', strtotime($data_header['tanggal'])); ?></td>
        </tr>
        <tr>
            <td>Nama Pelanggan</td>
            <td>: <strong><?= $data_header['nama_pelanggan']; ?></strong></td>
        </tr>
        <tr>
            <td>Total Bayar</td>
            <td>: <strong>Rp <?= number_format($data_header['total']); ?></strong></td>
        </tr>
    </table>
</div>

<div class="table-box">
    <h3>Barang yang Dibeli</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_detail = mysqli_query($koneksi, "SELECT * FROM detail 
                                                    JOIN produk ON detail.id_produk = produk.id_produk 
                                                    WHERE detail.id_transaksi = '$id_transaksi'");
            
            $no = 1;
            while($d = mysqli_fetch_array($query_detail)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $d['nama_produk']; ?></td>
                <td>Rp <?= number_format($d['harga']); ?></td>
                <td><?= $d['qty']; ?></td>
                <td>Rp <?= number_format($d['subtotal']); ?></td>
            </tr>
            <?php } ?>
            
            <tr style="background: #f9f9f9; font-weight: bold;">
                <td colspan="4" align="right">GRAND TOTAL</td>
                <td>Rp <?= number_format($data_header['total']); ?></td>
            </tr>
        </tbody>
    </table>

    <br>
    <a href="dashboard.php" class="btn">Kembali</a>
    
    <button onclick="window.print()" class="btn-primary" style="margin-left: 10px;">Cetak Struk</button>
</div>

<?php include '../layouts/footer.php'; ?>