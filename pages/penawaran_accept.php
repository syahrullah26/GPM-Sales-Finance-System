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
                        <div class="card shadow rounded-4 p-4">
                            <h4 class="mb-4 fw-bold"><i class="fas fa-file-invoice me-2"></i> Form Pembuatan Invoice</h4>

                            <form action="pages/penawaran_accept_proses.php" method="POST">
                                <!-- Informasi Invoice -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold text-primary mb-3">🧾 Informasi Invoice</h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">No Surat Penawaran</label>
                                            <input type="text" name="no_sp" class="form-control" value="<?= htmlspecialchars($data['no_sp']) ?>" readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">No PO <span class="badge bg-danger">Wajib</span></label>
                                            <input type="text" name="no_po" class="form-control" placeholder="Masukan No PO" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">No Surat Jalan <span class="badge bg-danger">Wajib</span></label>
                                            <div class="input-group">
                                                <input type="text" name="no_sj" id="inputNoSJ" class="form-control" placeholder="Masukan No Surat Jalan" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="bukaModalSJ()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">Klik tombol untuk melihat No SJ yang pernah digunakan.</small>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">No Invoice <span class="badge bg-danger">Wajib</span></label>
                                            <div class="input-group">
                                                <input type="text" name="no_invoice" id="inputNoInvoice" class="form-control" placeholder="Masukan No Invoice" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="bukaModalInvoice()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">Klik tombol untuk melihat No Invoice yang pernah digunakan.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden Data -->
                                <input type="hidden" name="penawaran_id" value="<?= $data['id'] ?>">

                                <!-- Data Perusahaan -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold text-primary mb-3">🏢 Data Perusahaan</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Perusahaan</label>
                                            <input type="text" name="perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Alamat</label>
                                            <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold text-primary mb-3">📅 Tanggal</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Penawaran</label>
                                            <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Invoice <span class="badge bg-danger">Wajib</span></label>
                                            <input type="date" name="tanggal_invoice" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Jatuh Tempo <span class="badge bg-danger">Wajib</span></label>
                                            <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pajak -->
                                <div class="form-group mb-4">
                                    <h5 class="fw-semibold text-primary mb-3">💸 Pajak</h5>
                                    <div class="d-flex gap-3">
                                        <input type="radio" class="btn-check" name="pajak" id="pajakYa" value="ya" autocomplete="off" required>
                                        <label class="btn btn-outline-primary px-4 py-2 rounded-3 shadow-sm" for="pajakYa">
                                            <i class="fas fa-check-circle me-2"></i> Dengan Pajak (11%)
                                        </label>

                                        <input type="radio" class="btn-check" name="pajak" id="pajakTidak" value="tidak" autocomplete="off" checked>
                                        <label class="btn btn-outline-danger px-4 py-2 rounded-3 shadow-sm" for="pajakTidak">
                                            <i class="fas fa-ban me-2"></i> Tanpa Pajak
                                        </label>
                                    </div>
                                </div>

                                <!-- Detail Produk -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold text-primary mb-3">📦 Detail Produk</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-sm align-middle" id="produkPenawaran">
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
                                                            <input type="text" name="nama_barang[]" class="form-control" value="<?= htmlspecialchars($item['nama_barang']) ?>" readonly>
                                                        </td>
                                                        <td><input type="number" name="quantity[]" class="form-control" value="<?= $item['quantity'] ?>" readonly></td>
                                                        <td><input type="text" name="satuan[]" class="form-control" value="<?= htmlspecialchars($item['satuan']) ?>" readonly></td>
                                                        <td><input type="number" name="harga_beli[]" class="form-control" value="<?= $item['harga_beli'] ?>" readonly></td>
                                                        <td><input type="number" name="harga_jual[]" class="form-control" value="<?= $item['harga_jual'] ?>" readonly></td>
                                                        <td><input type="text" name="keterangan[]" class="form-control" value="<?= htmlspecialchars($item['keterangan']) ?>" readonly></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="index.php?page=penawaran" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> Buat Invoice
                                    </button>
                                </div>
                            </form>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal No Invoice -->
<div class="modal fade" id="modalInvoice" tabindex="-1" aria-labelledby="modalInvoiceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInvoiceLabel">
                    <i class="fas fa-history me-2"></i> Riwayat No Invoice -
                    <span id="namaPerusahaanInvoice" class="fw-normal"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="listNoInvoice">
                    <li class="list-group-item text-muted text-center">Silakan tunggu...</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal No SJ -->
<div class="modal fade" id="modalSJ" tabindex="-1" aria-labelledby="modalSJLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSJLabel">
                    <i class="fas fa-history me-2"></i> Riwayat No Surat Jalan -
                    <span id="namaPerusahaanSJ" class="fw-normal"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="listNoSJ">
                    <li class="list-group-item text-muted text-center">Silakan tunggu...</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
    function bukaModalInvoice() {
        const perusahaan = document.querySelector('input[name="perusahaan"]').value;
        const list = document.getElementById('listNoInvoice');
        const label = document.getElementById('namaPerusahaanInvoice');
        list.innerHTML = '<li class="list-group-item text-center text-muted">Memuat data...</li>';
        label.textContent = perusahaan; 
        fetch(`pages/ajax_get_no_invoice.php?perusahaan=${encodeURIComponent(perusahaan)}`)
            .then(res => res.text())
            .then(html => {
                list.innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalInvoice')).show();
            })
            .catch(err => {
                list.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data</li>';
            });
    }

    function bukaModalSJ() {
        const perusahaan = document.querySelector('input[name="perusahaan"]').value;
        const list = document.getElementById('listNoSJ');
        const label = document.getElementById('namaPerusahaanSJ');
        list.innerHTML = '<li class="list-group-item text-center text-muted">Memuat data...</li>';
        label.textContent = perusahaan; 

        fetch(`pages/ajax_get_no_sj.php?perusahaan=${encodeURIComponent(perusahaan)}`)
            .then(res => res.text())
            .then(html => {
                list.innerHTML = html;
                new bootstrap.Modal(document.getElementById('modalSJ')).show();
            })
            .catch(err => {
                list.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data</li>';
            });
    }

    function isiNoInvoice(no_invoice) {
        const input = document.getElementById('inputNoInvoice');
        if (input) input.value = no_invoice;
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalInvoice'));
        modal.hide();
    }

    function isiNoSJ(no_sj) {
        const input = document.getElementById('inputNoSJ');
        if (input) input.value = no_sj;
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSJ'));
        modal.hide();
    }
</script>