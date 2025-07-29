<?php
include 'includes/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($konek, "SELECT * FROM penawaran WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data penawaran tidak ditemukan.";
    exit;
}

$items = mysqli_query($konek, "SELECT * FROM penawaran_items WHERE penawaran_id = $id");
?>

<head>
    <!-- MUAT Bootstrap LEBIH DULU -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (untuk ikon trash, dll) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSS Custom kamu -->
    <link rel="stylesheet" href="assets/css/Beranda.css">
</head>



<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Final Check Penawaran</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Penawaran</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <h4>Final Check Surat Penawaran No : <b><?= htmlspecialchars($data['no_sp']) ?></b></h4>
                        <p class="card-text">cek data dan masukan beberapa hal yang diperlukan untuk membuat <strong><span>Invoice</span></strong> dan <strong><span>Surat Jalan</span></strong> pastikan semua hal telah <strong>Sesuai.</strong></p>
                        <p class="card-text" style="color: red;">*masukan data yang diperlukan yang berlabel warna merah</p>
                        <form action="pages/penawaran_accept_proses.php" method="POST">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>No Surat Penawaran:</label>
                                        <input type="text" name="no_sp" class="form-control" value="<?= htmlspecialchars($data['no_sp']) ?>" required readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label style="color: red;" >No PO:</label>
                                        <input class="form-control" placeholder="Masukan No PO" type="text" name="no_po" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label style="color: red;">No Surat Invoice:</label>
                                        <input class="form-control" placeholder="Masukan No Invoice" type="text" name="no_invoice" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label style="color: red;">No Surat Jalan:</label>
                                        <input class="form-control" placeholder="Masukan No Surat Jalan" type="text" name="no_sj" required>
                                    </div>
                                </div>
                                <input type="hidden" name="penawaran_id" value="<?= $data['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Perusahaan</label>
                                    <input type="text" name="perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" required readonly>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label " >Tanggal Penawaran</label>
                                        <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" style="color: red;">Tanggal Invoice</label>
                                        <input type="date" name="tanggal_invoice" class="form-control" required >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" style="color: red;">Jatuh Tempo</label>
                                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" required >
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">Detail Produk</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="produkPenawaran">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_id[]" value="<?= $item['id'] ?>">
                                                    <input type="text" name="nama_barang[]" class="form-control" value="<?= htmlspecialchars($item['nama_barang']) ?>" required readonly>
                                                </td>
                                                <td><input type="number" name="quantity[]" class="form-control" value="<?= $item['quantity'] ?>" required readonly></td>
                                                <td><input type="text" name="satuan[]" class="form-control" value="<?= htmlspecialchars($item['satuan']) ?>" required readonly></td>
                                                <td><input type="number" name="harga_beli[]" class="form-control" value="<?= $item['harga_beli'] ?>" required readonly></td>
                                                <td><input type="number" name="harga_jual[]" class="form-control" value="<?= $item['harga_jual'] ?>" required readonly></td>
                                                <td><input type="text" name="keterangan[]" class="form-control" value="<?= htmlspecialchars($item['keterangan']) ?>" required readonly></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Buat Invoice</button>
                                <a href="index.php?page=penawaran" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>