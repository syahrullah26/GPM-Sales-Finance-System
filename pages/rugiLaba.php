<?php
include 'includes/koneksi.php';
$filterPerusahaan = $_POST['perusahaan'] ?? '';
$tanggalAwal = $_POST['tanggal_awal'] ?? '';
$tanggalAkhir = $_POST['tanggal_akhir'] ?? '';
///ongoing invoices query
$where = [];

if (!empty($filterPerusahaan)) {
    $perusahaan = mysqli_real_escape_string($konek, $filterPerusahaan);
    $where[] = "perusahaan = '$perusahaan'";
}

if (!empty($tanggalAwal)) {
    $where[] = "tanggal_invoice >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where[] = "tanggal_invoice <= '$tanggalAkhir'";
}


if (!empty($tanggalAwal) && !empty($tanggalAkhir) && $tanggalAwal > $tanggalAkhir) {
    echo "<script>
        alert('Tanggal awal tidak boleh lebih dari tanggal akhir.');
        window.history.back();
    </script>";
}


$where[] = "status = 'belum bayar'";
$where[] = "jatuh_tempo >= CURDATE()";

$sql = "SELECT * FROM invoices";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY DATE(created_at) = CURDATE() DESC, created_at DESC";


$dataInvoice = mysqli_query($konek, $sql);

$where = "";
if (isset($_POST['filterSubmit']) && !empty($_POST['perusahaan'])) {
    $perusahaan = mysqli_real_escape_string($konek, $_POST['perusahaan']);
    $where = "WHERE perusahaan = '$perusahaan'";
}

$invoices = mysqli_query($konek, $sql);
/// paid invoices query

$where2 = [];

if (!empty($filterPerusahaan)) {
    $perusahaan = mysqli_real_escape_string($konek, $filterPerusahaan);
    $where2[] = "perusahaan = '$perusahaan'";
}

if (!empty($tanggalAwal)) {
    $where2[] = "tanggal_invoice >= '$tanggalAwal'";
}

if (!empty($tanggalAkhir)) {
    $where2[] = "tanggal_invoice <= '$tanggalAkhir'";
}


if (!empty($tanggalAwal) && !empty($tanggalAkhir) && $tanggalAwal > $tanggalAkhir) {
    echo "<script>
        alert('Tanggal awal tidak boleh lebih dari tanggal akhir.');
        window.history.back();
    </script>";
}


$where2[] = "status = 'sudah bayar'";

$paidsql = "SELECT * FROM invoices";
if (!empty($where2)) {
    $paidsql .= " WHERE " . implode(" AND ", $where2);
}

$paidsql .= " ORDER BY tanggal_bayar DESC";


// $dataInvoice = mysqli_query($konek, $sql);

$where2 = "";
if (isset($_POST['filterSubmit']) && !empty($_POST['perusahaan'])) {
    $perusahaan = mysqli_real_escape_string($konek, $_POST['perusahaan']);
    $where2 = "WHERE perusahaan = '$perusahaan'";
}

$paidinvoices = mysqli_query($konek, $paidsql);
?>

<head>
    <link href="assets/css/styleBeranda.css" rel="stylesheet">
    <link href="assets/css/print.css" rel="stylesheet" media="print">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .alert {
            animation: fadeInDown 0.5s ease-in-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-sm i {
            margin-right: 4px;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 4px 8px;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: black;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .btn-sm:hover {
            opacity: 0.9;
        }

        .badge {
            display: inline-block;
            padding: 0.5em 0.75em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .bg-warning {
            background-color: orangered !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }
    </style>
</head>

<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Invoices & Surat Jalan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Invoices & Surat Jalan</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Invoices dan Surat Jalan</h5>
                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="perusahaan" class="form-label">Filter Perusahaan</label>
                                        <select name="perusahaan" id="perusahaan" class="form-select">
                                            <option value="">-- Semua Perusahaan --</option>
                                            <?php
                                            $getPerusahaan = mysqli_query($konek, "SELECT DISTINCT perusahaan FROM invoices");
                                            while ($row = mysqli_fetch_assoc($getPerusahaan)) {
                                                $selected = (isset($_POST['perusahaan']) && $_POST['perusahaan'] == $row['perusahaan']) ? 'selected' : '';
                                                echo "<option value='{$row['perusahaan']}' $selected>" . htmlspecialchars($row['perusahaan']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                                            value="<?= isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : '' ?>">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                                            value="<?= isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : '' ?>">
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12 text-end">
                                            <button type="submit" name="filterSubmit" class="btn btn-primary">Filter</button>
                                            <a href="index.php?page=rugi_laba" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mt-3">
                                    <p class="card-title fw-bold mb-2">Keterangan Tombol:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <a href="pages/penawaran_view.php?id=<?= $pnw['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Surat Penawaran">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Menampilkan Invoice.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-secondary me-1" title="Surat Jalan">
                                                <i class="fas fa-truck"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Menampilkan Surat Jalan.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-warning me-1" title="Edit Penawaran">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Mengubah atau mengedit Invoice & Surat Jalan (Contoh : No Invoice, Tambah dan Hapus Barang).</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-success me-1" title="Terima Pembayaran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk menerima pembayaran invoice yang sudah diterima.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-danger" title="Tolak Penawaran">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Menghapus Invoice & Surat Jalan.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" target="_blank" class="btn btn-success btn-sm mb-3">
                                                <i class="fas fa-file-excel"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Export Rugi Laba dari PT.Gangsar Purnama Mandiri.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $queryAlert = "SELECT * FROM invoices WHERE status = 'belum bayar' AND jatuh_tempo < CURDATE()";
                $resultAlert = mysqli_query($konek, $queryAlert);
                $overdueRows = mysqli_num_rows($resultAlert);
                if ($overdueRows > 0):
                ?>
                    <div class="alert alert-danger mt-2" style="font-size: 100%; padding: 1.25rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 1.5em; margin-right: 8px;"></i>
                        Terdapat <b><?= htmlspecialchars($overdueRows) ?></b> invoices yang sudah melewati tanggal <strong>jatuh tempo</strong> dan <strong>belum melakukan pembayaran</strong>.
                        <a href="index.php?page=overdue_invoice" style="font-weight: bold;">
                            <i class="fas fa-eye"></i> klik di sini untuk melihat invoicenya.
                        </a>
                    </div>
                <?php endif; ?>


                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-trash-alt me-2"></i>
                        <strong>Sukses!</strong> Invoice berhasil <strong>dihapus</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-info alert-dismissible fade show shadow-sm animate__animated animate__fadeInDown" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Berhasil!</strong> Invoice berhasil <strong>diperbarui</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Invoices & Surat Jalan</h5>
                            <span class="text-muted mb-5">pada halaman ini akan menampilkan data invoices & surat jalan dari <strong>PT. Gangsar Purnama Mandiri</strong> yang telah dibuat, pilih <strong>Paid Invoice</strong> untuk melihat invoice yang telah bayar.</span><br><br>
                            <a href="pages/export_rugi_laba.php?perusahaan=<?= urlencode($filterPerusahaan) ?>&tanggal_awal=<?= $tanggalAwal ?>&tanggal_akhir=<?= $tanggalAkhir ?>" target="_blank" class="btn btn-success btn-sm mb-3">
                                <i class="fas fa-file-excel"></i> Export ke Excel
                            </a>


                            <ul class="nav nav-tabs d-flex mb-3" id="invoiceTab" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 active" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab" aria-controls="ongoing" aria-selected="true">
                                        Ongoing Invoice
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">
                                        Paid Invoice
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="invoiceTabContent">
                                <div class="tab-pane fade show active" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                                    <div class="row">
                                        <div class="card">
                                            <div class="card-body">

                                                <h5 class="card-title">Ongoing Invoice</h5>
                                                <div class="flex justify-center mb-3">
                                                    <h5 class="card-title mr-2">Search:</h5>
                                                    <input type="text" id="searchInput" class="form-control mb-5" placeholder="Search...">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const searchInput = document.getElementById('searchInput');
                                                        const table = document.getElementById('ongoing-data');
                                                        const tr = table.getElementsByTagName('tr');

                                                        searchInput.addEventListener('keyup', function() {
                                                            const filter = searchInput.value.toLowerCase();

                                                            for (let i = 1; i < tr.length; i++) {
                                                                let visible = false;
                                                                let td = tr[i].getElementsByTagName('td');

                                                                for (let j = 0; j < td.length; j++) {
                                                                    if (td[j] && td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                                                                        visible = true;
                                                                        break;
                                                                    }
                                                                }

                                                                tr[i].style.display = visible ? "" : "none";
                                                            }
                                                        });
                                                    });
                                                </script>

                                                <span class="text-muted">Pada Halaman ini akan menampilkan data invoices yang <strong>belum melakukan pembayaran</strong> dan <strong>tanggal jatuh tempo</strong>. </span>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-sm table-hover" id="ongoing-data">
                                                        <thead class="table-dark text-center">
                                                            <tr>
                                                                <th rowspan="2">No</th>
                                                                <th rowspan="2">Perusahaan</th>
                                                                <th rowspan="2">No Invoice</th>
                                                                <th rowspan="2">No PO</th>
                                                                <th rowspan="2">Tanggal Invoice</th>
                                                                <th rowspan="2">Jatuh Tempo</th>
                                                                <th rowspan="2">Pajak</th>
                                                                <th rowspan="2">Status</th>
                                                                <th rowspan="2">Aksi</th>
                                                                <th colspan="9">Detail Produk</th>
                                                                <th rowspan="2">Total Beli</th>
                                                                <th rowspan="2">Total Jual</th>
                                                                <th rowspan="2">PPN</th>
                                                                <th rowspan="2">Total Jual Setelah Pajak</th>
                                                                <th rowspan="2">Total Laba</th>
                                                                <th rowspan="2">Presentase</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Nama Barang</th>
                                                                <th>Qty</th>
                                                                <th>Satuan</th>
                                                                <th>Harga Beli</th>
                                                                <th>Jumlah Beli</th>
                                                                <th>Harga Jual</th>
                                                                <th>Jumlah Jual</th>
                                                                <th>Laba</th>
                                                                <th>Presentase</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $no = 1;
                                                            while ($inv = mysqli_fetch_assoc($invoices)):
                                                                $items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $inv['id']);
                                                                $rowspan = mysqli_num_rows($items);
                                                                $first = true;

                                                                $total_beli = 0;
                                                                $total_jual = 0;
                                                                $total_laba = 0;

                                                                while ($item = mysqli_fetch_assoc($items)):
                                                                    $jumlah_beli = $item['quantity'] * $item['harga_beli'];
                                                                    $jumlah_jual = $item['quantity'] * $item['harga_jual'];
                                                                    $laba = $jumlah_jual - $jumlah_beli;
                                                                    $persentase = $jumlah_beli > 0 ? ($laba / $jumlah_beli) * 100 : 0;


                                                                    $total_beli += $jumlah_beli;
                                                                    $total_jual += $jumlah_jual;
                                                                    $total_laba += $laba;
                                                            ?>
                                                                    <tr>
                                                                        <?php if ($first): ?>
                                                                            <td rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                                                                            <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($inv['perusahaan']) ?></td>
                                                                            <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($inv['no_invoice']) ?></td>
                                                                            <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($inv['no_po']) ?></td>
                                                                            <td rowspan="<?= $rowspan ?>"><?= $inv['tanggal_invoice'] ?></td>
                                                                            <td rowspan="<?= $rowspan ?>"><?= $inv['jatuh_tempo'] ?></td>
                                                                            <td rowspan="<?= $rowspan ?>">
                                                                                <?php if ($inv['pajak'] === 'ya'): ?>
                                                                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Dengan Pajak</span>
                                                                                <?php else: ?>
                                                                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Tanpa Pajak</span>
                                                                                <?php endif; ?>
                                                                            </td>


                                                                            <td rowspan="<?= $rowspan ?>">
                                                                                <?php if ($inv['status'] === 'belum bayar'): ?>
                                                                                    <span class="badge bg-warning text-light"><i class="fas fa-clock"></i>
                                                                                        <?= htmlspecialchars($inv['status']) ?></span>
                                                                                <?php else: ?>
                                                                                    <span class="badge bg-success"><i class="fas fa-circle-check"></i>
                                                                                        <?= htmlspecialchars($inv['status']) ?></span>
                                                                                <?php endif; ?>
                                                                            </td>

                                                                            <td rowspan="<?= $rowspan ?>" class="text-nowrap">
                                                                                <a href="pages/invoice_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Invoice">
                                                                                    <i class="fas fa-file-invoice"></i>
                                                                                </a>
                                                                                <a href="pages/surat_jalan_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-secondary me-1" title="Surat Jalan">
                                                                                    <i class="fas fa-truck"></i>
                                                                                </a>
                                                                                <a href="index.php?page=invoice_edit&id=<?= $inv['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit Invoice">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </a>
                                                                                <a href="pages/invoice_bayar.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-success me-1" title="Terima Pembayaran" onclick="return confirm('Terima pembayaran ini?')">
                                                                                    <i class="fas fa-check-circle"></i>
                                                                                </a>

                                                                                <a href="pages/invoice_delete.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-danger" title="Hapus Invoice" onclick="return confirm('Yakin ingin menghapus invoice ini?')">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </a>
                                                                            </td>
                                                                        <?php endif; ?>

                                                                        <td><?= $item['nama_barang'] ?></td>
                                                                        <td><?= $item['quantity'] ?></td>
                                                                        <td><?= $item['satuan'] ?></td>
                                                                        <td>Rp. <?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($jumlah_beli, 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($jumlah_jual, 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($laba, 0, ',', '.') ?></td>
                                                                        <td>Rp. <?= number_format($persentase, 2) ?>%</td>

                                                                        <?php if ($first): ?>
                                                                            <td rowspan="<?= $rowspan ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_beli, 0, ',', '.') ?></td>
                                                                            <td rowspan="<?= $rowspan ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_jual, 0, ',', '.') ?></td>
                                                                            <td rowspan="<?= $rowspan ?>" class="text-end">Rp. <?= number_format($inv['ppn'], 0, ',', '.') ?></td>
                                                                            <td rowspan="<?= $rowspan ?>" class="fw-bold text-end text-success">Rp. <?= number_format($total_jual + $inv['ppn'], 0, ',', '.') ?></td>
                                                                            <td rowspan="<?= $rowspan ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_laba, 0, ',', '.') ?></td>
                                                                            <td rowspan="<?= $rowspan ?>" class="table-success fw-bold text-end">
                                                                                <?= $total_beli > 0 ? number_format(($total_laba / $total_beli) * 100, 2) . '%' : '0%' ?>
                                                                            </td>

                                                                        <?php $first = false;
                                                                        endif; ?>
                                                                    </tr>
                                                            <?php endwhile;
                                                            endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                                <div class="row">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Paid Invoice</h5>
                                            <span class="text-muted">Pada Halaman ini akan menampilkan data invoices yang <strong>sudah melakukan pembayaran</strong>.</span>
                                            <div class="flex justify-center mb-3">
                                                <h5 class="card-title mr-2">Search:</h5>
                                                <input type="text" id="searchInputPaid" class="form-control mb-5" placeholder="Search..">
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const searchInput = document.getElementById('searchInputPaid');
                                                    const table = document.getElementById('paid-data');
                                                    const tr = table.getElementsByTagName('tr');

                                                    searchInput.addEventListener('keyup', function() {
                                                        const filter = searchInput.value.toLowerCase();

                                                        for (let i = 1; i < tr.length; i++) {
                                                            let visible = false;
                                                            let td = tr[i].getElementsByTagName('td');

                                                            for (let j = 0; j < td.length; j++) {
                                                                if (td[j] && td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                                                                    visible = true;
                                                                    break;
                                                                }
                                                            }

                                                            tr[i].style.display = visible ? "" : "none";
                                                        }
                                                    });
                                                });
                                            </script>

                                            <div class="row mt-2">
                                                <?php
                                                date_default_timezone_set('Asia/Jakarta');
                                                setlocale(LC_TIME, 'id_ID.UTF-8');

                                                $tanggal = new DateTime();
                                                $weekNumber = ceil($tanggal->format('j') / 7);
                                                $namaBulan = strftime('%B', $tanggal->getTimestamp());
                                                $tahun = $tanggal->format('Y');
                                                $mingguIni = "Minggu ke-$weekNumber $namaBulan $tahun";

                                                $hariIni = date('Y-m-d');
                                                $awalMinggu = date('Y-m-d', strtotime('monday this week'));
                                                $akhirMinggu = date('Y-m-d', strtotime('sunday this week'));
                                                $awalBulan = date('Y-m-01');
                                                $akhirBulan = date('Y-m-t');
                                                function hitungLaba($konek, $start, $end)
                                                {
                                                    $query = "SELECT i.id, ii.quantity, ii.harga_beli, ii.harga_jual
                                                                FROM invoices i
                                                                JOIN invoice_items ii ON ii.invoice_id = i.id
                                                                WHERE i.status = 'sudah bayar' AND i.tanggal_bayar BETWEEN '$start' AND '$end'";
                                                    $result = mysqli_query($konek, $query);

                                                    $totalLaba = 0;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $jumlah_beli = $row['quantity'] * $row['harga_beli'];
                                                        $jumlah_jual = $row['quantity'] * $row['harga_jual'];
                                                        $totalLaba += $jumlah_jual - $jumlah_beli;
                                                    }
                                                    return $totalLaba;
                                                }

                                                $labaHariIni = hitungLaba($konek, $hariIni, $hariIni);
                                                $labaMingguIni = hitungLaba($konek, $awalMinggu, $akhirMinggu);
                                                $labaBulanIni = hitungLaba($konek, $awalBulan, $akhirBulan);
                                                ?>
                                                <div class="col-md-4 mb-3">
                                                    <div class="card shadow-sm rounded-3">
                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <i class="fas fa-calendar-alt text-primary"></i> Laba Bulan Ini
                                                                <br><small class="text-muted"><?= date('F Y') ?> </small>
                                                            </h6>
                                                            <h4>Rp. <?= number_format($labaHariIni) ?></h4>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-4 mb-3">
                                                    <div class="card shadow-sm rounded-3">
                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <i class="fas fa-calendar-week text-success"></i> Laba Minggu Ini
                                                                <br><small class="text-muted"><?= htmlspecialchars($mingguIni) ?></small>
                                                            </h6>
                                                            <h4>Rp. <?= number_format($labaMingguIni) ?></h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <div class="card shadow-sm rounded-3">
                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <i class="fas fa-calendar-day text-danger"></i> Laba Hari Ini
                                                                <br><small class="text-muted"><?= date('d F Y') ?></small>
                                                            </h6>
                                                            <h4>Rp <?= number_format($labaHariIni) ?></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm table-hover" id="paid-data">
                                                <thead class="table-dark text-center">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Perusahaan</th>
                                                        <th rowspan="2">No Invoice</th>
                                                        <th rowspan="2">No PO</th>
                                                        <th rowspan="2">Tanggal Invoice</th>
                                                        <th rowspan="2">Jatuh Tempo</th>
                                                        <th rowspan="2">Pajak</th>
                                                        <th rowspan="2">Tanggal Bayar</th>
                                                        <th rowspan="2">Status</th>
                                                        <th rowspan="2">Aksi</th>
                                                        <th colspan="9">Detail Produk</th>
                                                        <th rowspan="2">Total Beli</th>
                                                        <th rowspan="2">Total Jual</th>
                                                        <th rowspan="2">PPN</th>
                                                        <th rowspan="2">Total Jual Setelah Pajak</th>
                                                        <th rowspan="2">Total Laba</th>
                                                        <th rowspan="2">Presentase</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Nama Barang</th>
                                                        <th>Qty</th>
                                                        <th>Satuan</th>
                                                        <th>Harga Beli</th>
                                                        <th>Jumlah Beli</th>
                                                        <th>Harga Jual</th>
                                                        <th>Jumlah Jual</th>
                                                        <th>Laba</th>
                                                        <th>Presentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1;
                                                    while ($paidInv = mysqli_fetch_assoc($paidinvoices)):
                                                        $paiditems = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $paidInv['id']);
                                                        $rowspan_paid = mysqli_num_rows($paiditems);
                                                        $first = true;

                                                        $total_beli_paid = 0;
                                                        $total_jual_paid = 0;
                                                        $total_laba_paid = 0;

                                                        while ($paiditem = mysqli_fetch_assoc($paiditems)):
                                                            $jumlah_beli_paid = $paiditem['quantity'] * $paiditem['harga_beli'];
                                                            $jumlah_jual_paid = $paiditem['quantity'] * $paiditem['harga_jual'];
                                                            $laba_paid = $jumlah_jual_paid - $jumlah_beli_paid;
                                                            $persentase_paid = $jumlah_beli_paid > 0 ? ($laba_paid / $jumlah_beli_paid) * 100 : 0;


                                                            $total_beli_paid += $jumlah_beli_paid;
                                                            $total_jual_paid += $jumlah_jual_paid;
                                                            $total_laba_paid += $laba_paid;
                                                    ?>
                                                            <tr>
                                                                <?php if ($first): ?>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= $no++ ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= htmlspecialchars($paidInv['perusahaan']) ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= htmlspecialchars($paidInv['no_invoice']) ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= htmlspecialchars($paidInv['no_po']) ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= $paidInv['tanggal_invoice'] ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= $paidInv['jatuh_tempo'] ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>">
                                                                        <?php if ($paidInv['pajak'] === 'ya'): ?>
                                                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Dengan Pajak</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Tanpa Pajak</span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <td rowspan="<?= $rowspan_paid ?>"><?= $paidInv['tanggal_bayar'] ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>">
                                                                        <?php if ($paidInv['status'] === 'belum bayar'): ?>
                                                                            <span class="badge bg-warning text-light"><i class="fas fa-clock"></i>
                                                                                <?= htmlspecialchars($paidInv['status']) ?></span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-success"><i class="fas fa-circle-check"></i>
                                                                                <?= htmlspecialchars($paidInv['status']) ?></span>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <td rowspan="<?= $rowspan_paid ?>" class="text-nowrap">
                                                                        <a href="pages/invoice_view.php?id=<?= $paidInv['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Invoice">
                                                                            <i class="fas fa-file-invoice"></i>
                                                                        </a>
                                                                        <a href="pages/surat_jalan_view.php?id=<?= $paidInv['id'] ?>" class="btn btn-sm btn-secondary me-1" title="Surat Jalan">
                                                                            <i class="fas fa-truck"></i>
                                                                        </a>
                                                                    </td>
                                                                <?php endif; ?>

                                                                <td><?= $paiditem['nama_barang'] ?></td>
                                                                <td><?= $paiditem['quantity'] ?></td>
                                                                <td><?= $paiditem['satuan'] ?></td>
                                                                <td>Rp. <?= number_format($paiditem['harga_beli'], 0, ',', '.') ?></td>
                                                                <td>Rp. <?= number_format($jumlah_beli_paid, 0, ',', '.') ?></td>
                                                                <td>Rp. <?= number_format($paiditem['harga_jual'], 0, ',', '.') ?></td>
                                                                <td>Rp. <?= number_format($jumlah_jual_paid, 0, ',', '.') ?></td>
                                                                <td>Rp. <?= number_format($laba_paid, 0, ',', '.') ?></td>
                                                                <td>Rp. <?= number_format($persentase_paid, 2) ?>%</td>

                                                                <?php if ($first): ?>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_beli_paid, 0, ',', '.') ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_jual_paid, 0, ',', '.') ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="text-end">Rp. <?= number_format($paidInv['ppn'], 0, ',', '.') ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="fw-bold text-end text-success">Rp. <?= number_format($total_jual_paid + $paidInv['ppn'], 0, ',', '.') ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="table-success fw-bold text-end">Rp. <?= number_format($total_laba_paid, 0, ',', '.') ?></td>
                                                                    <td rowspan="<?= $rowspan_paid ?>" class="table-success fw-bold text-end">
                                                                        <?= $total_beli_paid > 0 ? number_format(($total_laba_paid / $total_beli_paid) * 100, 2) . '%' : '0%' ?>
                                                                    </td>

                                                                <?php $first = false;
                                                                endif; ?>
                                                            </tr>
                                                    <?php endwhile;
                                                    endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- End Card -->
            </div>
        </div>
        </div>
    </section>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ongoing-data').DataTable();
        $('#paid-data').DataTable();
        $('#expired-data').DataTable();
    });
</script>
<script>
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 500000000);
</script>