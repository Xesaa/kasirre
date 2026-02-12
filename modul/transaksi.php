<?php 
include '../layouts/header.php'; 


$cek_umum = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE nama_pelanggan='Umum'");
if (mysqli_num_rows($cek_umum) == 0) {
  
    mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, alamat, no_hp) VALUES ('Umum', '-', '-')");
}

$data_umum = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE nama_pelanggan='Umum'"));
$id_umum   = $data_umum['id_pelanggan'];



if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}


if (isset($_POST['tambah'])) {
    $id_produk = $_POST['produk'];
    $qty       = $_POST['qty'];

   
    $q_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id_produk'");
    $d_produk = mysqli_fetch_array($q_produk);


    if ($d_produk['stok'] < $qty) {
        echo "<script>alert('Stok tidak cukup! Sisa stok: " . $d_produk['stok'] . "');</script>";
    } else {
    
        $sudah_ada = false;
        foreach ($_SESSION['keranjang'] as $key => $item) {
            if ($item['id'] == $id_produk) {
                $_SESSION['keranjang'][$key]['qty'] += $qty;
                $_SESSION['keranjang'][$key]['subtotal'] = $_SESSION['keranjang'][$key]['qty'] * $item['harga'];
                $sudah_ada = true;
                break;
            }
        }

     
        if (!$sudah_ada) {
            $barang = [
                'id'    => $id_produk,
                'nama'  => $d_produk['nama_produk'],
                'harga' => $d_produk['harga'],
                'qty'   => $qty,
                'subtotal' => $d_produk['harga'] * $qty
            ];
            $_SESSION['keranjang'][] = $barang;
        }
    }
}


if (isset($_GET['hapus_item'])) {
    $index = $_GET['hapus_item'];
    unset($_SESSION['keranjang'][$index]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); 
    echo "<script>window.location='transaksi.php';</script>";
}


if (isset($_POST['bayar'])) {
    $id_pelanggan = $_POST['id_pelanggan']; 
    $tanggal      = date('Y-m-d');
    $total_bayar  = $_POST['total_bayar'];

    if (empty($_SESSION['keranjang'])) {
        echo "<script>alert('Keranjang masih kosong!');</script>";
    } else {
       
        $simpan = mysqli_query($koneksi, "INSERT INTO transaksi (tanggal, id_pelanggan, total) 
                                          VALUES ('$tanggal', '$id_pelanggan', '$total_bayar')");
        
        if ($simpan) {
            $id_transaksi = mysqli_insert_id($koneksi);

            foreach ($_SESSION['keranjang'] as $item) {
                $id_produk = $item['id'];
                $qty       = $item['qty'];
                $subtotal  = $item['subtotal'];

          
                mysqli_query($koneksi, "INSERT INTO detail (id_transaksi, id_produk, qty, subtotal)
                                        VALUES ('$id_transaksi', '$id_produk', '$qty', '$subtotal')");

                mysqli_query($koneksi, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
            }

            $_SESSION['keranjang'] = [];
       
            echo "<script>
                alert('Transaksi Berhasil!'); 
                window.location='detail.php?id=$id_transaksi';
            </script>";
        }
    }
}
?>

<h2>Kasir Transaksi</h2>

<div class="cards" style="grid-template-columns: 2fr 1fr; margin-bottom: 20px;">
    
    <div class="table-box">
        <h3>1. Masukkan Produk</h3>
        <form method="post">
            <table style="width: 100%;">
                <tr>
                    <td>
                        <select name="produk" required style="font-size: 16px; padding: 10px;">
                            <option value="">-- Pilih Produk --</option>
                            <?php
                            $pro = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");
                            while($p = mysqli_fetch_array($pro)) {
                            ?>
                            <option value="<?= $p['id_produk']; ?>">
                                <?= $p['nama_produk']; ?> (Stok: <?= $p['stok']; ?>)
                            </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td width="100">
                        <input type="number" name="qty" value="1" min="1" required style="padding: 10px;">
                    </td>
                    <td width="100">
                        <button type="submit" name="tambah" class="btn-primary" style="width:100%; padding: 10px;">+ Tambah</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="table-box" style="background: #fdfdfd;">
        <h3>Info Pelanggan</h3>
        <label>Pilih Pembeli:</label>
        <select name="id_pelanggan" form="form-bayar" required style="padding: 10px; margin-top: 5px;">
            <option value="<?= $id_umum; ?>" selected>-- UMUM (Tanpa Data) --</option>
            
            <option disabled>----------------</option>
            <?php
            $pel = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE nama_pelanggan != 'Umum' ORDER BY nama_pelanggan ASC");
            while ($pl = mysqli_fetch_array($pel)) {
            ?>
            <option value="<?= $pl['id_pelanggan']; ?>"><?= $pl['nama_pelanggan']; ?></option>
            <?php } ?>
        </select>
    </div>

</div>

<div class="table-box">
    <h3>2. List Belanjaan</h3>
    <form method="post" id="form-bayar"> <table>
            <thead>
                <tr style="background:#f9f9f9;">
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_akhir = 0;
                foreach ($_SESSION['keranjang'] as $key => $k) {
                    $total_akhir += $k['subtotal'];
                ?>
                <tr>
                    <td><?= $k['nama']; ?></td>
                    <td>Rp <?= number_format($k['harga']); ?></td>
                    <td><?= $k['qty']; ?></td>
                    <td>Rp <?= number_format($k['subtotal']); ?></td>
                    <td>
                        <a href="transaksi.php?hapus_item=<?= $key; ?>" style="color:red; text-decoration:none;">✖</a>
                    </td>
                </tr>
                <?php } ?>
                
                <?php if (!empty($_SESSION['keranjang'])) { ?>
                <tr style="font-weight:bold; background:#eee; font-size: 18px;">
                    <td colspan="3" align="right">TOTAL HARUS DIBAYAR</td>
                    <td colspan="2" style="color: #90353D;">Rp <?= number_format($total_akhir); ?></td>
                </tr>
                <?php } else { ?>
                    <tr><td colspan="5" align="center" style="padding: 20px; color: #999;">Keranjang masih kosong. Yuk tambah produk!</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if (!empty($_SESSION['keranjang'])) { ?>
            <div style="margin-top: 20px; text-align: right;">
                <input type="hidden" name="total_bayar" value="<?= $total_akhir; ?>">
                <button type="submit" name="bayar" class="btn-primary" style="padding: 15px 40px; font-size: 18px;">
                     PROSES PEMBAYARAN
                </button>
            </div>
        <?php } ?>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>