<?php 

include '../layouts/header.php'; 


if (isset($_POST['simpan'])) {
    $nama     = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga    = $_POST['harga'];
    $stok     = $_POST['stok'];

   
    $query = "INSERT INTO produk (nama_produk, id_kategori, harga, stok) 
              VALUES ('$nama', '$kategori', '$harga', '$stok')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Berhasil menambah produk!'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan!');</script>";
    }
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    try {
        $hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk = '$id'");
        
        if ($hapus) {
            echo "<script>alert('Data berhasil dihapus'); window.location='produk.php';</script>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<script>
            alert('GAGAL MENGHAPUS! Produk ini sudah ada di riwayat transaksi. Data tidak bisa dihapus demi keamanan laporan.');
            window.location='produk.php';
        </script>";
    }
}
?>

<h2>Data Produk</h2>

<div class="table-box" style="margin-bottom: 25px;">
  <h3>Tambah Produk Baru</h3>
  
  <form method="post">
    <table>
      <tr>
        <td width="200">Nama Produk</td>
        <td><input type="text" name="nama" placeholder="Masukkan nama produk" required></td>
      </tr>
      <tr>
    <td>Kategori</td>
    <td>
        <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
         
            $tampil_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
            
        
            while($k = mysqli_fetch_array($tampil_kategori)) {
            ?>
                <option value="<?php echo $k['id_kategori']; ?>">
                    <?php echo $k['nama_kategori']; ?>
                </option>
            <?php } ?>
        </select>
    </td>
</tr>
      <tr>
        <td>Harga (Rp)</td>
        <td><input type="number" name="harga" placeholder="Contoh: 15000" required></td>
      </tr>
      <tr>
        <td>Stok Awal</td>
        <td><input type="number" name="stok" placeholder="Contoh: 10" required></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <button class="btn-primary" type="submit" name="simpan">Simpan Data</button>
        </td>
      </tr>
    </table>
  </form>
</div>

<div class="table-box">
  <h3>Daftar Stok Produk</h3>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
   
      $no = 1;
 
      $query_tampil = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
      
      while ($data = mysqli_fetch_array($query_tampil)) {
    
          $warna_stok = ($data['stok'] < 5) ? 'color: red; font-weight: bold;' : 'color: green;';
      ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($data['nama_produk']); ?></td>
        <td>
            <?php 
          
            if($data['id_kategori'] == 1) echo "Makanan";
            elseif($data['id_kategori'] == 2) echo "Minuman";
            else echo "Lainnya";
            ?>
        </td>
        <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
        <td style="<?= $warna_stok; ?>"><?= $data['stok']; ?> pcs</td>
        <td>
          <a href="edit_produk.php?id=<?= $data['id_produk']; ?>" class="btn">Edit</a>
          
          <a href="produk.php?hapus=<?= $data['id_produk']; ?>" 
             class="btn" 
             style="border-color: red; color: red;"
             onclick="return confirm('Yakin ingin menghapus <?= $data['nama_produk']; ?>?')">
             Hapus
          </a>
        </td>
      </tr>
      <?php } ?>
      
      <?php if(mysqli_num_rows($query_tampil) == 0) { ?>
        <tr>
            <td colspan="6" style="text-align:center;">Belum ada data produk.</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php include '../layouts/footer.php'; ?>