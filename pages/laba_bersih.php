<?php

$bulan = $_POST['bulan'] ?? date('m');
$tahun = $_POST['tahun'] ?? date('Y');

$query = "
    SELECT * FROM invoices 
    WHERE status = 'sudah bayar' 
    AND MONTH(tanggal_invoice) = '$bulan' 
    AND YEAR(tanggal_invoice) = '$tahun'
    ORDER BY tanggal_invoice ASC
";

$bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);

$tanggalAwal = "$tahun-$bulanPadded-01";
$tanggalAkhir = date("Y-m-t", strtotime($tanggalAwal));

$tanggalAwalLabel = date("d M Y", strtotime($tanggalAwal));
$tanggalAkhirLabel = date("d M Y", strtotime($tanggalAkhir));
$invoices = mysqli_query($konek, $query);
?>
<style>
    .laporan-title {
        font-size: 1.75rem;
        font-weight: bold;
        color: #343a40;
        border-left: 5px solid #198754;
        padding-left: 15px;
        margin-bottom: 0.5rem;
    }

    .laporan-subtitle {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
</style>
<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Laporan Rugi Laba Bulanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Laporan Rugi Laba Bulanan</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-3">
                                <h4 class="laporan-title">
                                    Laporan Laba Bulanan
                                </h4>
                                <div class="laporan-subtitle">
                                    Periode: <?= $tanggalAwalLabel ?> - <?= $tanggalAkhirLabel ?>
                                </div>
                                <div class="laporan-subtitle">
                                    *Data yang tampil merupakan list invoice yang telah melakukan pembayaran.
                                </div>

                                <br>

                            </div>

                            <form method="POST" class="row g-3 mb-4">
                                <div class="col-md-2">
                                    <select name="bulan" class="form-select">
                                        <?php
                                        for ($m = 1; $m <= 12; $m++) {
                                            $selected = $bulan == $m ? 'selected' : '';
                                            echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="tahun" class="form-select">
                                        <?php
                                        for ($y = 2022; $y <= date('Y'); $y++) {
                                            $selected = $tahun == $y ? 'selected' : '';
                                            echo "<option value='$y' $selected>$y</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                            <div class="row col-md-2">
                                <a href="pages/laba_bersih_export.php?tanggal_awal=<?= $tanggalAwal ?>&tanggal_akhir=<?= $tanggalAkhir ?>" target="_blank" class="btn btn-success btn-sm mb-3">
                                    <i class="fas fa-file-excel"></i> Export ke Excel
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm table-hover datatable">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Perusahaan</th>
                                            <th rowspan="2">No Invoice</th>
                                            <th rowspan="2">Tanggal Invoice</th>
                                            <th colspan="3">Total</th>
                                            <th rowspan="2">Presentase</th>
                                        </tr>
                                        <tr>
                                            <th>Total Beli</th>
                                            <th>Total Jual</th>
                                            <th>Total Laba</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $grandBeli = $grandJual = $grandLaba = 0;

                                        while ($inv = mysqli_fetch_assoc($invoices)) {
                                            $items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $inv['id']);

                                            $totalBeli = 0;
                                            $totalJual = 0;

                                            while ($item = mysqli_fetch_assoc($items)) {
                                                $totalBeli += $item['quantity'] * $item['harga_beli'];
                                                $totalJual += $item['quantity'] * $item['harga_jual'];
                                            }

                                            $laba = $totalJual - $totalBeli;
                                            $persen = $totalBeli > 0 ? ($laba / $totalBeli) * 100 : 0;

                                            $grandBeli += $totalBeli;
                                            $grandJual += $totalJual;
                                            $grandLaba += $laba;
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($inv['perusahaan']) ?></td>
                                                <td><?= htmlspecialchars($inv['no_invoice']) ?></td>
                                                <td><?= $inv['tanggal_invoice'] ?></td>
                                                <td class="text-end">Rp. <?= number_format($totalBeli, 0, ',', '.') ?></td>
                                                <td class="text-end">Rp. <?= number_format($totalJual, 0, ',', '.') ?></td>
                                                <td class="text-end">Rp. <?= number_format($laba, 0, ',', '.') ?></td>
                                                <td class="text-end"><?= number_format($persen, 2) ?>%</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot class="table-dark">
                                        <tr>
                                            <th colspan="4" class="text-center">Total</th>
                                            <th class="text-end">Rp. <?= number_format($grandBeli, 0, ',', '.') ?></th>
                                            <th class="text-end">Rp. <?= number_format($grandJual, 0, ',', '.') ?></th>
                                            <th class="text-end">Rp. <?= number_format($grandLaba, 0, ',', '.') ?></th>
                                            <th class="text-end"><?= $grandBeli > 0 ? number_format(($grandLaba / $grandBeli) * 100, 2) . '%' : '0%' ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>