<?php 
include '../layouts/header.php'; 


if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nohp   = $_POST['nohp'];

    $simpan = mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, alamat, no_hp) 
                                      VALUES ('$nama', '$alamat', '$nohp')");
    
    if ($simpan) {
        echo "<script>alert('Pelanggan berhasil ditambahkan'); window.location='pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan');</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    try {
        $hapus = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id_pelanggan='$id'");
        
        if ($hapus) {
            echo "<script>alert('Data berhasil dihapus'); window.location='pelanggan.php';</script>";
        }
    } catch (mysqli_sql_exception $e) {
      
        echo "<script>
            alert('GAGAL MENGHAPUS! Pelanggan ini memiliki riwayat transaksi. Data tidak bisa dihapus demi keamanan laporan.');
            window.location='pelanggan.php';
        </script>";
    }
}
?>

<h2>Data Pelanggan</h2>

<div class="table-box" style="margin-bottom:25px;">
  <h3>Tambah Pelanggan Baru</h3>

  <form method="post">
    <table>
      <tr>
        <td width="200">Nama Pelanggan</td>
        <td><input type="text" name="nama" placeholder="Masukkan nama" required></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td><input type="text" name="alamat" placeholder="Masukkan alamat lengkap"></td>
      </tr>
      <tr>
        <td>No. HP</td>
        <td><input type="number" name="nohp" placeholder="Contoh: 08123456789"></td>
      </tr>
      <tr>
        <td></td>
        <td><button class="btn-primary" type="submit" name="simpan">Simpan Pelanggan</button></td>
      </tr>
    </table>
  </form>
</div>

<div class="table-box">
  <h3>Daftar Pelanggan</h3>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
 
      $no = 1;
      $query = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
      
      while ($data = mysqli_fetch_array($query)) {
      ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($data['nama_pelanggan']); ?></td>
        <td><?= htmlspecialchars($data['alamat']); ?></td>
        <td><?= htmlspecialchars($data['no_hp']); ?></td>
        <td>
          <a href="edit_pelanggan.php?id=<?= $data['id_pelanggan']; ?>" class="btn">Edit</a>
          <a href="pelanggan.php?hapus=<?= $data['id_pelanggan']; ?>" 
             class="btn" 
             style="border-color: red; color: red;"
             onclick="return confirm('Yakin hapus pelanggan ini?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
      
      <?php if(mysqli_num_rows($query) == 0) { ?>
        <tr><td colspan="5" align="center">Belum ada data pelanggan.</td></tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php include '../layouts/footer.php'; ?>