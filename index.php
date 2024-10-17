<?php
//Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "data_table";

//buat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

//kd_brg otomatis
$q = mysqli_query($koneksi, "SELECT kd_brg FROM barang order by kd_brg desc limit 1");
$datax = mysqli_fetch_array($q);
if ($datax) {
    $no_terakhir = substr($datax['kd_brg'], -3);
    $no = $no_terakhir + 1;

    if ($no > 0 and $no < 10) {
        $kd_brg = "00" . $no;
    } else if ($no > 10 and $no < 100) {
        $kd_brg = "0" . $no;
    } else if ($no > 100) {
        $kd_brg = $no;
    }
} else {
    $kd_brg = "001";
}

$tahun = date('Y');
$vkd_brg = "IVN-" . $tahun . '-' . $kd_brg;
//INV-2022-001


//jika tombol simpan diklik
if (isset($_POST['bsimpan'])) {

    //pengujian apakah data akan diedit atau disimpan baru
    if (isset($_GET['hal']) == "edit") {
        //data akan di edit
        $edit = mysqli_query($koneksi, "UPDATE barang SET
                                               nm_brg = '$_POST[tnm_brg]',
                                               asal_barang = '$_POST[tasal_barang]',
                                               jumlah = '$_POST[tjumlah]',
                                               satuan = '$_POST[tsatuan]',
                                               tgl_diterima = '$_POST[ttgl_diterima]',
                                               nm_penerima = '$_POST[tnm_penerima]'
                                        WHERE id = '$_GET[id]'
                                       ");

        //uji jika edit data sukses
        if ($edit) {
            echo "<script>
                alert('Edit data Sukses!');
                document.location='index.php';
             </script>";
        } else {
            echo "<script>
                alert('Edit data Gagal!');
                document.location='index.php';
             </script>";
        }
    } else {
        //Data akan disimpan baru
        $simpan = mysqli_query($koneksi, " INSERT INTO barang (kd_brg, nm_brg, asal_barang, jumlah, satuan, tgl_diterima, nm_penerima)
                                            VALUE ( '$_POST[tkd_brg]',
                                                    '$_POST[tnm_brg]',                                         
                                                    '$_POST[tasal_barang]',                                         
                                                    '$_POST[tjumlah]',                                         
                                                    '$_POST[tsatuan]',                                         
                                                    '$_POST[ttgl_diterima]',
                                                    '$_POST[tnm_penerima]')
                                                ");
        //uji jika simpan data sukses
        if ($simpan) {
            echo "<script>
            alert('Simpan data Sukses!');
            document.location='index.php';
            </script>";
        } else {
            echo "<script>
            alert('Simpan data Gagal!');
            document.location='index.php';
            </script>";
        }
    }
}

//deklarasi variabel untuk menampung data yang akan diedit

$vnm_brg = "";
$vasal_barang = "";
$vjumlah = "";
$vsatuan = "";
$vtgl_diterima = "";
$vnm_penerima = "";


//pengujian jika tombol edit / hapus diklik
if (isset($_GET['hal'])) {

    //pengujian jika edit data
    if ($_GET['hal'] == "edit") {

        //tampilkan data yang akan diedit
        $tampil = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = '$_GET[id]' ");
        $data = mysqli_fetch_array($tampil);
        if ($data) {
            //jika data ditemukan, maka data di tampung ke dalam variabel
            $vkd_brg = $data['kd_brg'];
            $vnm_brg = $data['nm_brg'];
            $vasal_barang = $data['asal_barang'];
            $vnm_penerima = $data['nm_penerima'];
            $vjumlah = $data['jumlah'];
            $vsatuan = $data['satuan'];
            $vtgl_diterima = $data['tgl_diterima'];
        }
    } else if ($_GET['hal'] == "hapus") {
        //persiapan hapus data
        $hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id = '$_GET[id]' ");
        //uji jika hapus data sukses
        if ($hapus) {
            echo "<script>
            alert('Hapus data Sukses!');
            document.location='index.php';
            </script>";
        } else {
            echo "<script>
            alert('Hapus data Gagal!');
            document.location='index.php';
            </script>";
        }
    }
}








?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD PHP & MySQL + Bootstrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
    <!-- awal container -->
    <div class="container">
        <h3 class="text-center">Data Inventaris</h3>
        <h3 class="text-center">Kantor Ngodingpintar</h3>

        <!-- awal row -->
        <div class="row">
            <!-- awal col -->
            <div class="col-md-8 mx-auto">
                <!-- awal card -->
                <div class="card">
                    <div class="card-header bg-info text-light">
                        Form Input Data Barang
                    </div>
                    <div class="card-body">
                        <!-- Awal Form -->
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="tkd_brg" value="<?= $vkd_brg ?>" class="form-control" placeholder="Masukkan kode Barang">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="tnm_brg" value="<?= $vnm_brg ?>" class="form-control" placeholder="Masukkan nama Barang">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="tnm_penerima" value="<?= $vnm_penerima ?>" class="form-control" placeholder="Masukkan nama penerima">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Asal Barang</label>
                                <select class="form-select" name="tasal_barang">
                                    <option value="<?= $vasal_barang ?>"><?= $vasal_barang ?></option>
                                    <option value="Pembelian">Pembelian</option>
                                    <option value="Hibah">Hibah</option>
                                    <option value="Bantuan">Bantuan</option>
                                    <option value="Sumbangan">Sumbangan</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="tjumlah" value="<?= $vjumlah ?>" class="form-control" placeholder="Masukkan Jumlah Barang">
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <select class="form-select" name="tsatuan">
                                            <option value="<?= $vsatuan ?>"><?= $vsatuan ?></option>
                                            <option value="Unit">Unit</option>
                                            <option value="Kotak">Kotak</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Pak">Pak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal diterima</label>
                                        <input type="date" name="ttgl_diterima" value="<?= $vtgl_diterima ?>" class="form-control" placeholder="Masukkan Jumlah Barang">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <hr>
                                    <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                                    <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                                </div>

                            </div>



                        </form>

                        <!-- Akhir Form -->


                    </div>
                    <div class="card-footer bg-info">

                    </div>
                </div>
                <!-- akhir card -->
            </div>
            <!-- akhir col -->
        </div>
        <!-- akhir row -->

        <!-- awal card -->
        <div class="card mt-3">
            <div class="card-header bg-info text-light">
                Data Barang
            </div>
            <div class="card-body">
                <div class="col-md-6 mx-auto">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="tcari" value="<?= @$_POST['tcari'] ?>" class="form-control" placeholder="Masukkan kata kunci!">
                            <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                            <button class="btn btn-danger" name="breset" type="submit">Reset</button>
                        </div>
                    </form>
                </div>

                <table class="table table-striped table-hover table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Asal Barang</th>
                        <th>Nama penerima</th>
                        <th>Jumlah</th>
                        <th>Tanggal diterima</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    //persiapan menampilkan data
                    $no = 1;

                    //untuk pencarian data
                    //jika tombol cari di klik
                    if (isset($_POST['bcari'])) {
                        //tampilkan data yang di cari
                        $keyword = $_POST['tcari'];
                        $q = "SELECT * FROM barang WHERE kd_brg like '%$keyword%' or nm_brg like '%$keyword%' or asal_barang like '%$keyword%' or nm_penerima like '%$keyword%' order by id desc  ";
                    } else {
                        $q = "SELECT * FROM barang order by id desc";
                    }

                    $tampil = mysqli_query($koneksi, $q);
                    while ($data = mysqli_fetch_array($tampil)) :
                    ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['kd_brg'] ?></td>
                            <td><?= $data['nm_brg'] ?></td>
                            <td><?= $data['asal_barang'] ?></td>
                            <td><?= $data['nm_penerima'] ?></td>
                            <td><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
                            <td><?= $data['tgl_diterima'] ?></td>
                            <td>
                                <a href="index.php?hal=edit&id=<?= $data['id'] ?>" class="btn btn-warning">Edit</a>

                                <a href="index.php?hal=hapus&id=<?= $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah anda Yakin akan Hapus Data ini?')">Hapus</a>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                </table>


            </div>
            <div class="card-footer bg-info">

            </div>
        </div>
        <!-- akhir card -->





    </div>
    <!-- akhir container -->



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>