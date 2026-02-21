<?php
include 'includes/koneksi.php';


setlocale(LC_TIME, 'id_ID.UTF-8'); 
date_default_timezone_set('Asia/Jakarta'); 


$tanggalHariIni = strftime('%A, %d %B %Y', strtotime('today')); 


$tanggal = new DateTime();
$weekNumber = ceil($tanggal->format('j') / 7); 
$namaBulan = strftime('%B', $tanggal->getTimestamp()); 
$tahun = $tanggal->format('Y');
$mingguIni = "Minggu ke-$weekNumber $namaBulan $tahun"; 

$bulanIni = strftime('%B, %Y'); 




$today = date('Y-m-d');
$queryHari = mysqli_query($konek, "SELECT SUM(total_pengeluaran) AS total FROM pengeluaran WHERE tanggal = '$today'");
$totalHari = mysqli_fetch_assoc($queryHari)['total'] ?? 0;

$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
$queryMinggu = mysqli_query($konek, "SELECT SUM(total_pengeluaran) AS total FROM pengeluaran WHERE tanggal BETWEEN '$startOfWeek' AND '$endOfWeek'");
$totalMinggu = mysqli_fetch_assoc($queryMinggu)['total'] ?? 0;


$bulanIni = date('Y-m');
$queryBulan = mysqli_query($konek, "SELECT SUM(total_pengeluaran) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulanIni'");
$totalBulan = mysqli_fetch_assoc($queryBulan)['total'] ?? 0;
$filterPerusahaan = $_POST['perusahaan'] ?? '';
$filterStatus = $_POST['status'] ?? '';

$where = [];


if (!empty($filterPerusahaan)) {
    $safePerusahaan = mysqli_real_escape_string($konek, $filterPerusahaan);
    $where[] = "nama_perusahaan = '$safePerusahaan'";
}

if (!isset($_POST['filterSubmit'])) {
    $where[] = "status = 'menunggu'";
} elseif (!empty($filterStatus)) {
    $safeStatus = mysqli_real_escape_string($konek, $filterStatus);
    $where[] = "status = '$safeStatus'";
}


$sql = "SELECT * FROM penawaran";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal DESC";

$dataPenawaran = mysqli_query($konek, $sql) or die("Query Error: " . mysqli_error($konek));
?>

<head>
    <link href="assets/css/styleBeranda.css" rel="stylesheet">
    <link href="assets/css/print.css" rel="stylesheet" media="print">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="assets/vendor/select2/select2.min.css" rel="stylesheet" />
    <script src="assets/vendor/select2/select2.min.js"></script>
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

        ul {
            padding-left: 1.2rem;
        }

        ul li {
            list-style-type: disc;
        }
    </style>
</head>

<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Pengeluaran</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Pengeluaran</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
 
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calendar-alt text-primary"></i> Pengeluaran Bulan Ini
                                    <br><small class="text-muted"><?= date('F Y') ?> </small>
                                </h6>
                                <h4>Rp <?= number_format($totalBulan, 0, ',', '.') ?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calendar-week text-success"></i> Pengeluaran Minggu Ini
                                    <br><small class="text-muted"><?= $mingguIni ?></small>
                                </h6>
                                <h4>Rp <?= number_format($totalMinggu, 0, ',', '.') ?></h4>
                            </div>
                        </div>
                    </div>

             
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calendar-day text-danger"></i> Pengeluaran Hari Ini
                                    <br><small class="text-muted"><?= $tanggalHariIni ?></small>
                                </h6>
                                <h4>Rp <?= number_format($totalHari, 0, ',', '.') ?></h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Form Input Pengeluaran</h2>
                            <form class="row g-3" action="pages/pengeluaran_proses.php" id="formPengeluaran" method="post">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="form-group col-md-6">
                                            <label class="mb-2">No Pengeluaran:</label>
                                            <input class="form-control" placeholder="Masukan No Pengeluaran" type="text" name="no_pengeluaran" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="mb-2">Tanggal Pengeluaran:</label>
                                            <input class="form-control" type="date" name="tanggal" required>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="mb-2">Keterangan:</label>
                                        <textarea class="form-control" placeholder="Masukan keterangan Pengeluaran (Optional)" name="keterangan" rows="3"></textarea>
                                    </div>

                                    <div class="bg-secondary text-white text-center p-2 rounded mb-3">
                                        <h5 class="mb-0">Invoice Terkait</h5>
                                    </div>
                                    <p class="text-danger">*Pada Bagian ini pilih invoice yang terkait pada pengeluaran. Bisa memilih Lebih dari satu dengan cara menekan tombol tambah invoice</p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center align-middle" id="invoiceTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">No Invoice</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">
                                                        <select class="form-control invoice-select" name="invoice_ids[]" required style="width:100%;"></select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteInvoiceRow(this)">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-primary mb-4" type="button" onclick="addInvoiceRow()">+ Tambah Invoice</button>

                                    <div class="bg-secondary text-white text-center p-2 rounded mb-3">
                                        <h5 class="mb-0">Jenis Pengeluaran</h5>
                                    </div>
                                    <p class="text-danger">*Pada Bagian ini masukan jenis dan nominal pengeluaran yang dibutuhkan untuk operasional pada invoice terkait yang sudah di pilih sebelumnya.</p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center align-middle" id="jenisTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">Jenis</th>
                                                    <th class="text-center">Nominal (Rp)</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">
                                                        <input class="form-control" placeholder="Contoh : Bensin" type="text" name="jenis_pengeluaran[]" required>
                                                    </td>
                                                    <td class="text-start">
                                                        <input class="form-control" placeholder="Nominal" type="number" name="nominal_jenis[]" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteJenisRow(this)">
                                                            <i class="fas fa-trash-alt"></i> Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-primary mb-4" onclick="addJenisRow()">+ Tambah Jenis Pengeluaran</button>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Simpan Pengeluaran</button>
                                    </div>
                                </div>
                            </form>

                            <script>
                                function initSelect2() {
                                    $('.invoice-select').each(function() {
                                        if (!$(this).hasClass("select2-hidden-accessible")) {
                                            $(this).select2({
                                                placeholder: "Cari Nomor Invoice",
                                                allowClear: true,
                                                ajax: {
                                                    url: '/purnama/ajax/fetch_invoices.php',
                                                    dataType: 'json',
                                                    delay: 250,
                                                    data: function(params) {
                                                        return {
                                                            search: params.term
                                                        };
                                                    },
                                                    processResults: function(data) {
                                                        return {
                                                            results: data
                                                        };
                                                    },
                                                    cache: true
                                                },
                                                minimumInputLength: 1,
                                                width: 'resolve'
                                            });
                                        }
                                    });
                                }

                                $(document).ready(function() {
                                    initSelect2();
                                });

                                function addInvoiceRow() {
                                    const table = document.getElementById("invoiceTable").getElementsByTagName('tbody')[0];
                                    const newRow = document.createElement('tr');
                                    newRow.innerHTML = `
                                        <td class="text-start">
                                            <select class="form-control invoice-select" name="invoice_ids[]" required style="width:100%;"></select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteInvoiceRow(this)">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </td>`;
                                    table.appendChild(newRow);
                                    initSelect2();
                                }

                                function deleteInvoiceRow(btn) {
                                    const tableBody = document.getElementById("invoiceTable").getElementsByTagName('tbody')[0];
                                    const rows = tableBody.getElementsByTagName('tr');
                                    if (rows.length > 1) {
                                        const row = btn.closest("tr");
                                        row.remove();
                                    } else {
                                        alert("Minimal harus ada 1 Invoice Terkait.");
                                    }
                                }

                                function addJenisRow() {
                                    const table = document.getElementById("jenisTable").getElementsByTagName('tbody')[0];
                                    const newRow = document.createElement('tr');
                                    newRow.innerHTML = `
                                        <td class="text-start">
                                            <input class="form-control" placeholder="Contoh : Tol" type="text" name="jenis_pengeluaran[]" required>
                                        </td>
                                        <td class="text-start">
                                            <input class="form-control" placeholder="Nominal" type="number" name="nominal_jenis[]" step="0.01" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteJenisRow(this)">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </td>`;
                                    table.appendChild(newRow);
                                }

                                function deleteJenisRow(btn) {
                                    const tableBody = document.getElementById("jenisTable").getElementsByTagName('tbody')[0];
                                    const rows = tableBody.getElementsByTagName('tr');
                                    if (rows.length > 1) {
                                        const row = btn.closest("tr");
                                        row.remove();
                                    } else {
                                        alert("Minimal harus ada 1 baris jenis pengeluaran.");
                                    }
                                }
                            </script>

                            <script>
                                document.getElementById('formPengeluaran').addEventListener('submit', function(e) {
                                    const noPengeluaran = document.getElementById('no_pengeluaran').value.trim();
                                    const tanggal = document.getElementById('tanggal').value.trim();
                                    const invoice = document.querySelectorAll('select[name="invoice_id[]"]');
                                    const jenis = document.querySelectorAll('input[name="jenis_pengeluaran[]"]');
                                    const nominal = document.querySelectorAll('input[name="nominal[]"]');

                                    if (!noPengeluaran || !tanggal || invoice.length === 0 || jenis.length === 0 || nominal.length === 0) {
                                        alert("Pastikan semua field telah diisi!");
                                        e.preventDefault();
                                        return false;
                                    }

                                    for (let i = 0; i < jenis.length; i++) {
                                        if (!jenis[i].value.trim() || !nominal[i].value.trim()) {
                                            alert("Setiap jenis pengeluaran dan nominal harus diisi!");
                                            e.preventDefault();
                                            return false;
                                        }
                                    }

                                    for (let i = 0; i < invoice.length; i++) {
                                        if (!invoice[i].value.trim()) {
                                            alert("Setiap invoice harus dipilih!");
                                            e.preventDefault();
                                            return false;
                                        }
                                    }
                                });
                            </script>

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
                                                <a href="#" class="btn btn-sm btn-info me-2" title="Lihat Detail">
                                                    <i class="fas fa-eye me-1"></i>
                                                </a>
                                                <span class="text-muted">: Tombol untuk Menampilkan informasi lengkap pengeluaran.</span>
                                            </li>
                                            <li class="mb-2">
                                                <a href="#" class="btn btn-sm btn-warning me-2" title="Edit">
                                                    <i class="fas fa-edit me-1"></i>
                                                </a>
                                                <span class="text-muted">: Tombol untuk Mengubah data pengeluaran.</span>
                                            </li>
                                            <li class="mb-2">
                                                <a href="#" class="btn btn-sm btn-danger me-2" title="Hapus">
                                                    <i class="fas fa-trash-alt me-1"></i>
                                                </a>
                                                <span class="text-muted">: Tombol untuk Menghapus data pengeluaran.</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Filter Tanggal</h3>
                                <span class="text-muted">Pada bagian ini untuk melakukan filterisasi terhadap data yang ingin ditampilkan berdasarkan tanggal pengeluaran.</span>
                                <form method="get" class="mb-3">
                                    <div class="row align-items-end">
                                        <input type="hidden" name="page" value="pengeluaran"> 

                                        <div class="col-md-3">
                                            <label>Dari Tanggal:</label>
                                            <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Sampai Tanggal:</label>
                                            <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
                                        </div>

                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="submit" name="filter" class="btn btn-primary">Tampilkan</button>
                                                <a href="index.php?page=pengeluaran" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>&nbsp;</label>
                                            <div class="d-grid gap-1">
                                                <button type="submit" name="tampilkan_semua" class="btn btn-success">
                                                    <i class="fas fa-database"></i> Tampilkan Semua
                                                </button>
                                                <small class="text-muted">
                                                    Klik tombol ini untuk menampilkan semua data pengeluaran yang telah disimpan di database.
                                                </small>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <?php if (isset($_GET['filter']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])): ?>
                        <div class="alert alert-info mt-2">
                            Menampilkan data pengeluaran dari <strong><?= date('d-m-Y', strtotime($_GET['start_date'])) ?></strong>
                            hingga <strong><?= date('d-m-Y', strtotime($_GET['end_date'])) ?></strong>.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-2">
                            Menampilkan data pengeluaran untuk bulan ini: <strong><?= date('F Y') ?></strong>.
                        </div>
                    <?php endif; ?>



                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Pengeluaran PT. Gangsar Purnama Mandiri</h5>
                                <a href="pages/pengeluaran_export_excel.php?perusahaan=<?= urlencode($filterPerusahaan) ?>&tanggal_awal=<?= urlencode($tanggalAwal) ?>&tanggal_akhir=<?= urlencode($tanggalAkhir) ?>" target="_blank" class="btn btn-success btn-sm mb-3">
                                    <i class="fas fa-file-excel"></i> Export ke Excel
                                </a>

                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-hover datatable" id="dataPengeluaran">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">No Pengeluaran</th>
                                                <th rowspan="2">Tanggal</th>
                                                <th rowspan="2">Keterangan</th>
                                                <th rowspan="2">Total</th>
                                                <th rowspan="2">Aksi</th>
                                                <th rowspan="2">Jenis Pengeluaran</th>
                                                <th colspan="3">Invoice Terkait</th>
                                            </tr>
                                            <tr>
                                                <th>No Invoice</th>
                                                <th>Perusahaan</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $filterQuery = "SELECT * FROM pengeluaran";
                                            $conditions = [];

                                            if (isset($_GET['filter'])) {
                                                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                    $start = mysqli_real_escape_string($konek, $_GET['start_date']);
                                                    $end = mysqli_real_escape_string($konek, $_GET['end_date']);
                                                    $conditions[] = "tanggal BETWEEN '$start' AND '$end'";
                                                }
                                            } elseif (!isset($_GET['tampilkan_semua'])) {
                                              
                                                $month = date('m');
                                                $year = date('Y');
                                                $conditions[] = "MONTH(tanggal) = '$month' AND YEAR(tanggal) = '$year'";
                                            }

                                            if (!empty($conditions)) {
                                                $filterQuery .= " WHERE " . implode(" AND ", $conditions);
                                            }

                                            $filterQuery .= " ORDER BY tanggal DESC";
                                            $query = mysqli_query($konek, $filterQuery);

                                            while ($pgl = mysqli_fetch_assoc($query)) :                                               
                                                $jenis_list = [];
                                                $jenis_query = mysqli_query($konek, "SELECT * FROM pengeluaran_jenis WHERE pengeluaran_id = {$pgl['id']}");
                                                while ($jenis = mysqli_fetch_assoc($jenis_query)) {
                                                    $jenis_list[] = htmlspecialchars($jenis['jenis_pengeluaran']) . ' - Rp ' . number_format($jenis['nominal'], 0, ',', '.');
                                                }
                                                $jenis_text = implode("<br>", $jenis_list);

                                                $items = mysqli_query($konek, "
                                                SELECT pi.*, inv.no_invoice, inv.perusahaan 
                                                FROM pengeluaran_items pi 
                                                LEFT JOIN invoices inv ON pi.invoice_id = inv.id 
                                                WHERE pi.pengeluaran_id = {$pgl['id']}
                                            ");
                                                $rowspan = mysqli_num_rows($items);


                                                if ($rowspan === 0) {
                                                    echo '<tr>';
                                                    echo '<td>' . $no++ . '</td>';
                                                    echo '<td>' . htmlspecialchars($pgl['no_pengeluaran']) . '</td>';
                                                    echo '<td>' . date('d-m-Y', strtotime($pgl['tanggal'])) . '</td>';
                                                    echo '<td>' . nl2br(htmlspecialchars($pgl['keterangan'])) . '</td>';
                                                    echo '<td>' . number_format($pgl['total_pengeluaran'], 0, ',', '.') . '</td>';
                                                    echo '<td class="text-center text-nowrap">
                                                    <a href="pages/pengeluaran_view.php?id=' . $pgl['id'] . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>
                                                    <a href="index.php?page=pengeluaran_edit&id=' . $pgl['id'] . '" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                                    <a href="pages/pengeluaran_delete.php?id=' . $pgl['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin hapus data ini?\')"><i class="fas fa-trash-alt"></i></a>
                                                </td>';
                                                    echo '<td>' . $jenis_text . '</td>';
                                                    echo '<td colspan="3" class="text-center text-muted fst-italic">Tidak ada invoice terkait</td>';
                                                    echo '</tr>';
                                                } else {
                                                    $first = true;
                                                    while ($item = mysqli_fetch_assoc($items)) {
                                                        echo '<tr>';
                                                        if ($first) {
                                                            echo '<td rowspan="' . $rowspan . '">' . $no++ . '</td>';
                                                            echo '<td rowspan="' . $rowspan . '">' . htmlspecialchars($pgl['no_pengeluaran']) . '</td>';
                                                            echo '<td rowspan="' . $rowspan . '">' . date('d-m-Y', strtotime($pgl['tanggal'])) . '</td>';
                                                            echo '<td rowspan="' . $rowspan . '">' . nl2br(htmlspecialchars($pgl['keterangan'])) . '</td>';
                                                            echo '<td rowspan="' . $rowspan . '">' . number_format($pgl['total_pengeluaran'], 0, ',', '.') . '</td>';
                                                            echo '<td rowspan="' . $rowspan . '" class="text-center text-nowrap">
                                                                <a href="pages/pengeluaran_view.php?id=' . $pgl['id'] . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>
                                                                <a href="index.php?page=pengeluaran_edit&id=' . $pgl['id'] . '" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                                                <a href="pages/pengeluaran_delete.php?id=' . $pgl['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin hapus data ini?\')"><i class="fas fa-trash-alt"></i></a>
                                                            </td>';
                                                            echo '<td rowspan="' . $rowspan . '">' . $jenis_text . '</td>';
                                                            $first = false;
                                                        }
                                                        echo '<td>' . htmlspecialchars($item['no_invoice']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($item['perusahaan']) . '</td>';
                                                        echo '<td>' . number_format($item['nominal'], 0, ',', '.') . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                            endwhile;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
        </div>
    </section>
</main>