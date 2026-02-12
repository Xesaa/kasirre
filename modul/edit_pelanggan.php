<?php
include '../layouts/header.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan='$id'");
$data = mysqli_fetch_array($query);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $nohp   = $_POST['nohp'];

    $update = mysqli_query($koneksi, "UPDATE pelanggan SET 
                            nama_pelanggan='$nama', 
                            alamat='$alamat', 
                            no_hp='$nohp' 
                            WHERE id_pelanggan='$id'");

    if ($update) {
        echo "<script>alert('Data Berhasil Diupdate'); window.location='pelanggan.php';</script>";
    }
}
?>

<h2>Edit Pelanggan</h2>

<div class="table-box">
    <form method="post">
        <table>
            <tr>
                <td>Nama Pelanggan</td>
                <td><input type="text" name="nama" value="<?= $data['nama_pelanggan']; ?>" required></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><input type="text" name="alamat" value="<?= $data['alamat']; ?>"></td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td><input type="number" name="nohp" value="<?= $data['no_hp']; ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="btn-primary" type="submit" name="update">Update Data</button>
                    <a href="pelanggan.php" class="btn" style="border:none;">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>