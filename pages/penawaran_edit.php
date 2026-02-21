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
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/Beranda.css">
</head>



<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Edit Invoice</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Edit Invoice</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <h4>Edit Surat Penawaran No : <b><?= htmlspecialchars($data['no_sp']) ?></b></h4>
                        <form action="pages/penawaran_update.php" method="POST">
                            <input type="hidden" name="penawaran_id" value="<?= $data['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Nomor Surat Penawaran</label>
                                <input type="text" name="no_sp" class="form-control" value="<?= htmlspecialchars($data['no_sp']) ?>" required>
                            </div>
                            <div class="row">
                                <div class="mb-3 form-group col-md-6">
                                    <label class="form-label">Perusahaan</label>
                                    <input type="text" name="perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required>
                                </div>
                                <div class="mb-3 form-group col-md-6">
                                    <label class="form-label">Nama Penerima</label>
                                    <input type="text" name="penerima" class="form-control" value="<?= htmlspecialchars($data['penerima']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <input type="text" name="perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Penawaran</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_id[]" value="<?= $item['id'] ?>">
                                                    <input type="text" name="nama_barang[]" class="form-control" value="<?= htmlspecialchars($item['nama_barang']) ?>" required>
                                                </td>
                                                <td><input type="number" name="quantity[]" class="form-control" value="<?= $item['quantity'] ?>" required></td>
                                                <td><input type="text" name="satuan[]" class="form-control" value="<?= htmlspecialchars($item['satuan']) ?>" required></td>
                                                <td><input type="number" name="harga_beli[]" class="form-control" value="<?= $item['harga_beli'] ?>" required></td>
                                                <td><input type="number" name="harga_jual[]" class="form-control" value="<?= $item['harga_jual'] ?>" required></td>
                                                <td><input type="text" name="keterangan[]" class="form-control" value="<?= htmlspecialchars($item['keterangan']) ?>"></td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-danger"
                                                        onclick="hapusItemAjax(<?= $item['id'] ?>, this, event)">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>


                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-success btn-sm mb-3" onclick="tambahBaris()">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </button>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="index.php?page=penawaran" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function tambahBaris() {
            const tbody = document.querySelector('#produkPenawaran tbody');
            const tr = document.createElement('tr');

            tr.innerHTML = `
        <td><input type="text" name="nama_barang[]" class="form-control" required></td>
        <td><input type="number" name="quantity[]" class="form-control" required></td>
        <td><input type="text" name="satuan[]" class="form-control" required></td>
        <td><input type="number" name="harga_beli[]" class="form-control" step="any" required></td>
        <td><input type="number" name="harga_jual[]" class="form-control" step="any" required></td>
        <td><input type="text" name="keterangan[]" class="form-control"></td>
        <td class="text-center">
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="hapusBaris(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

            tbody.appendChild(tr);
        }
    </script>

    <script>
        function hapusItemAjax(itemId, el, event) {

            event.preventDefault();

            if (!confirm('Yakin ingin menghapus item ini?')) return;

            fetch(`pages/penawaran_hapus_item.php?id=${itemId}`, {
                    method: 'GET'
                })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === 'OK') {
                        el.closest('tr').remove();
                    } else {
                        alert('Gagal menghapus item: ' + result);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus item.');
                });
        }
    </script>