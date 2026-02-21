<?php
setlocale(LC_TIME, 'id_ID.UTF-8');
date_default_timezone_set('Asia/Jakarta');

$awalBulan = date('Y-m-01');
$akhirBulan = date('Y-m-t');

$tanggalAwalLabel = strftime('%e %B %Y', strtotime($awalBulan));
$tanggalAkhirLabel = strftime('%e %B %Y', strtotime($akhirBulan));


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
            padding: 4px 10px;
            border-radius: 0.5rem;
        }

        .btn-info,
        .btn-secondary,
        .btn-warning,
        .btn-danger,
        .btn-success {
            color: white;
            border: none;
        }

        .btn-warning {
            color: black;
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

        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            transition: 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card-title {
            font-weight: 600;
            color: #333;
        }

        h4 {
            font-weight: bold;
            color: #212529;
        }

        .text-muted {
            font-size: 0.85rem;
        }
    </style>
</head>

<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Rekap Rugi Laba</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Rekap Rugi Laba</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rekap Pengeluaran</h5>
                            <span class="text-muted">Berikut ini merupakan rekapan pengeluaran <strong>PT. Gangsar Purnama Mandiri</strong> secara <strong>Real Time</strong>.
                                <div class="row">
                                    <a href="index.php?page=laporan_pengeluaran&tanggal_awal=<?= $awalBulan ?>&tanggal_akhir=<?= $akhirBulan ?>" class="btn btn-sm btn-danger float-end mb-3">
                                        <i class="fas fa-filter"></i> Lihat pengeluaran dari <?= $tanggalAwalLabel ?> - <?= $tanggalAkhirLabel ?>
                                    </a>

                                </div>
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

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rekap Laba</h5>
                            <span class="text-muted">Berikut ini merupakan rekapan laba <strong>PT. Gangsar Purnama Mandiri</strong> secara <strong>Real Time</strong>.
                                <div class="row">
                                    <a href="index.php?page=laba_bersih&tanggal_awal=<?= $awalBulan ?>&tanggal_akhir=<?= $akhirBulan ?>" class="btn btn-sm btn-success float-end mb-3">
                                        <i class="fas fa-filter"></i> Lihat Laba dari <?= $tanggalAwalLabel ?> - <?= $tanggalAkhirLabel ?>
                                    </a>

                                </div>
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
                                                <h4>Rp <?= number_format($labaBulanIni) ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rekap Laba Bersih</h5>
                            <span class="text-muted">Berikut ini merupakan rekapan laba bersih yang telah di kurangi oleh pengeluaran operasional dari <strong>PT. Gangsar Purnama Mandiri</strong> secara <strong>Real Time</strong>.</span>

                            <?php
                            $labaBersihBulanIni = $labaBulanIni - $totalBulan;
                            $labaBersihMingguIni = $labaMingguIni - $totalMinggu;
                            $labaBersihHariIni = $labaHariIni - $totalHari;
                            ?>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm rounded-3">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-calendar-alt text-primary"></i> Laba Bulan Ini
                                                <br><small class="text-muted"><?= date('F Y') ?></small>
                                            </h6>
                                            <?php
                                            if ($labaBersihBulanIni > 0) {
                                                echo '<h4 class="text-success"><i class="fas fa-arrow-up"></i> Rp. ' . number_format($labaBersihBulanIni) . '</h4>';
                                            } elseif ($labaBersihBulanIni < 0) {
                                                echo '<h4 class="text-danger"><i class="fas fa-arrow-down"></i> Rp. ' . number_format($labaBersihBulanIni) . '</h4>';
                                            } else {
                                                echo '<h4 class="text-secondary"><i class="fas fa-minus-circle"></i> Rp. 0</h4>';
                                            }
                                            ?>
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
                                            <?php
                                            if ($labaBersihMingguIni > 0) {
                                                echo '<h4 class="text-success"><i class="fas fa-arrow-up"></i> Rp. ' . number_format($labaBersihMingguIni) . '</h4>';
                                            } elseif ($labaBersihMingguIni < 0) {
                                                echo '<h4 class="text-danger"><i class="fas fa-arrow-down"></i> Rp. ' . number_format($labaBersihMingguIni) . '</h4>';
                                            } else {
                                                echo '<h4 class="text-secondary"><i class="fas fa-minus-circle"></i> Rp. 0</h4>';
                                            }
                                            ?>
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
                                            <?php
                                            if ($labaBersihHariIni > 0) {
                                                echo '<h4 class="text-success"><i class="fas fa-arrow-up"></i> Rp. ' . number_format($labaBersihHariIni) . '</h4>';
                                            } elseif ($labaBersihHariIni < 0) {
                                                echo '<h4 class="text-danger"><i class="fas fa-arrow-down"></i> Rp. ' . number_format($labaBersihHariIni) . '</h4>';
                                            } else {
                                                echo '<h4 class="text-secondary"><i class="fas fa-minus-circle"></i> Rp. 0</h4>';
                                            }
                                            ?>
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