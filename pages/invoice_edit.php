<?php

include 'includes/koneksi.php';



if (!isset($_GET['id'])) {
    echo "ID invoice tidak ditemukan!";
    exit;
}

$id = (int)$_GET['id'];
$invoice = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id"));
if (!$invoice) {
    echo "Data invoice tidak ditemukan!";
    exit;
}

$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");
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
                        <h4>Edit Invoice No : <b><?= htmlspecialchars($invoice['no_invoice']) ?></b></h4>
                        <form action="pages/invoice_update.php" method="POST">
                            <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <input type="text" name="perusahaan" class="form-control" value="<?= htmlspecialchars($invoice['perusahaan']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($invoice['alamat']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No Invoice</label>
                                    <input type="text" name="no_invoice" class="form-control" value="<?= htmlspecialchars($invoice['no_invoice']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No PO</label>
                                    <input type="text" name="no_po" class="form-control" value="<?= htmlspecialchars($invoice['no_po']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No Surat Jalan</label>
                                    <input type="text" name="no_sj" class="form-control" value="<?= htmlspecialchars($invoice['no_sj']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Invoice</label>
                                    <input type="date" name="tanggal_invoice" class="form-control" value="<?= $invoice['tanggal_invoice'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo" class="form-control" value="<?= $invoice['jatuh_tempo'] ?>" required>
                                </div>
                            </div>

                            <h5 class="mt-4">Produk</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="productTable">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
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
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-success btn-sm mb-3" onclick="addRow()">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </button>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="index.php?page=rugiLaba" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function addRow() {
        const tableBody = document.getElementById("productTable").getElementsByTagName("tbody")[0];
        const newRow = tableBody.insertRow();

        newRow.innerHTML = `
            <td>
                <input type="hidden" name="item_id[]" value="">
                <input type="text" name="nama_barang[]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="quantity[]" class="form-control" required>
            </td>
            <td>
                <input type="text" name="satuan[]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="harga_beli[]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="harga_jual[]" class="form-control" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
    }

    function removeRow(button) {
        const row = button.closest("tr");
        if (row) row.remove();
    }
</script>
