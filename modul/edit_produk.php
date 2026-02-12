<?php
include '../layouts/header.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
$data  = mysqli_fetch_array($query);


if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='produk.php';</script>";
    exit;
}


if (isset($_POST['update'])) {
    $nama     = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga    = $_POST['harga'];
    $stok     = $_POST['stok'];

    $update = mysqli_query($koneksi, "UPDATE produk SET 
                            nama_produk='$nama', 
                            id_kategori='$kategori', 
                            harga='$harga', 
                            stok='$stok' 
                            WHERE id_produk='$id'");

    if ($update) {
        echo "<script>alert('Data Berhasil Diupdate'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal Update Data');</script>";
    }
}
?>

<h2>Edit Produk</h2>

<div class="table-box">
    <h3>Form Edit Produk</h3>
    <form method="post">
        <table>
            <tr>
                <td width="200">Nama Produk</td>
                <td>
                    <input type="text" name="nama" value="<?= $data['nama_produk']; ?>" required>
                </td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>
                    <select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                     
                        $q_kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                        while ($k = mysqli_fetch_array($q_kat)) {
                    
                            if ($data['id_kategori'] == $k['id_kategori']) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                        ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= $selected; ?>>
                                <?= $k['nama_kategori']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Harga (Rp)</td>
                <td>
                    <input type="number" name="harga" value="<?= $data['harga']; ?>" required>
                </td>
            </tr>
            <tr>
                <td>Stok</td>
                <td>
                    <input type="number" name="stok" value="<?= $data['stok']; ?>" required>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="btn-primary" type="submit" name="update">Simpan Perubahan</button>
                    <a href="produk.php" class="btn" style="border:none; margin-left: 10px;">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>