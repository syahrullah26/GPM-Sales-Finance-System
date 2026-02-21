<?php


$bulan = $_POST['bulan'] ?? date('m');
$tahun = $_POST['tahun'] ?? date('Y');

$bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
$start_date = "$tahun-$bulanPadded-01";
$end_date = date("Y-m-t", strtotime($start_date));

$tanggalAwalLabel = date("d M Y", strtotime($start_date));
$tanggalAkhirLabel = date("d M Y", strtotime($end_date));


$query = "
    SELECT p.*, 
           GROUP_CONCAT(CONCAT(j.jenis_pengeluaran, ' (Rp ', FORMAT(j.nominal, 0), ')') SEPARATOR ', ') AS rincian_jenis
    FROM pengeluaran p
    LEFT JOIN pengeluaran_jenis j ON j.pengeluaran_id = p.id
    WHERE MONTH(p.tanggal) = '$bulan' 
      AND YEAR(p.tanggal) = '$tahun'
    GROUP BY p.id
    ORDER BY p.tanggal ASC
";
$pengeluaran = mysqli_query($konek, $query);
?>

<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Laporan Pengeluaran Bulanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Laporan Pengeluaran Bulanan</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <h4 class="laporan-title">Laporan Pengeluaran Bulanan</h4>
                            <div class="laporan-subtitle">Periode: <?= $tanggalAwalLabel ?> - <?= $tanggalAkhirLabel ?></div>
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
                        <div class="row col-md-3">
                            <a href="pages/pengeluaran_export_excel.php?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" target="_blank" class="btn btn-success btn-sm mb-3">
                                <i class="fas fa-file-excel"></i> Export ke Excel
                            </a>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-striped table-sm table-hover datatable">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>No Pengeluaran</th>
                                        <th>Tanggal</th>
                                        <th>Rincian Jenis Pengeluaran</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $totalKeseluruhan = 0;

                                    while ($row = mysqli_fetch_assoc($pengeluaran)) {
                                        $totalKeseluruhan += $row['total_pengeluaran'];
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['no_pengeluaran']) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= $row['rincian_jenis'] ?: '-' ?></td>
                                            <td class="text-end">Rp. <?= number_format($row['total_pengeluaran'], 0, ',', '.') ?></td>
                                            <td><?= !empty($row['keterangan']) ? htmlspecialchars($row['keterangan']) : '-' ?></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="4" class="text-center">Total Keseluruhan</th>
                                        <th class="text-end">Rp. <?= number_format($totalKeseluruhan, 0, ',', '.') ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>