<?php
include 'includes/koneksi.php'; 
$filterPerusahaan = $_POST['perusahaan'] ?? '';
$tanggalAwal = $_POST['tanggal_awal'] ?? '';
$tanggalAkhir = $_POST['tanggal_akhir'] ?? '';

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


$sql = "SELECT * FROM invoices";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal_invoice DESC";


$dataInvoice = mysqli_query($konek, $sql);

$where = "";
if (isset($_POST['filterSubmit']) && !empty($_POST['perusahaan'])) {
    $perusahaan = mysqli_real_escape_string($konek, $_POST['perusahaan']);
    $where = "WHERE perusahaan = '$perusahaan'";
}

$invoices = mysqli_query($konek, $sql);
?>

<head>
    <link href="assets/css/styleBeranda.css" rel="stylesheet">
    <link href="assets/css/print.css" rel="stylesheet" media="print">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  
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
    </style>
</head>

<main id="full-width-main" class="full-width-main">
    <div class="pagetitle">
        <h1>Rugi Laba</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=beranda">Home</a></li>
                <li class="breadcrumb-item active">Rugi Laba</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Rugi Laba</h5>
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
                            <h5 class="card-title">Data Penjualan</h5>
                            <a href="pages/export_rugi_laba.php?perusahaan=<?= urlencode($filterPerusahaan) ?>&tanggal_awal=<?= $tanggalAwal ?>&tanggal_akhir=<?= $tanggalAkhir ?>" target="_blank" class="btn btn-success btn-sm mb-3">
                                <i class="fas fa-file-excel"></i> Export ke Excel
                            </a>
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

                            <div class="table-responsive">
                                <table class="table table-striped table-sm table-hover datatable" id="data">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Perusahaan</th>
                                            <th rowspan="2">No Invoice</th>
                                            <th rowspan="2">No Surat Jalan</th>
                                            <th rowspan="2">Tanggal Invoice</th>
                                            <th rowspan="2">Jatuh Tempo</th>
                                            <th rowspan="2">Jatuh Tempo</th>
                                            <th colspan="9">Detail Produk</th>
                                            <th rowspan="2">Aksi</th>
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
                                            <th>Proentase</th>
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
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= $no++ ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>">
                                                            <a href="index.php?page=invoice_edit&id=<?= $inv['id'] ?>">
                                                                <?= htmlspecialchars($inv['perusahaan']) ?>
                                                            </a>
                                                        </td>

                                                        <td rowspan="<?= $rowspan + 1 ?>">
                                                            <a href="pages/invoice_view.php?id=<?= $inv['id'] ?>">
                                                                <?= htmlspecialchars($inv['no_invoice']) ?>
                                                            </a>
                                                        </td>

                                                        <td rowspan="<?= $rowspan + 1 ?>">
                                                            <a href="pages/surat_jalan_view.php?id=<?= $inv['id'] ?>">
                                                                <?= htmlspecialchars($inv['no_sj']) ?>
                                                            </a>
                                                        </td>

                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= $inv['tanggal_invoice'] ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= $inv['jatuh_tempo'] ?></td>
                                                        <td rowspan="<?= $$rowspan + 1 ?>" class="text-nowrap">
                                                            <a href="pages/invoice_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Invoice">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </a>
                                                            <a href="pages/surat_jalan_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-secondary me-1" title="Surat Jalan">
                                                                <i class="fas fa-truck"></i>
                                                            </a>
                                                            <a href="index.php?page=invoice_edit&id=<?= $inv['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit Invoice">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <a href="pages/invoice_delete.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-danger" title="Hapus Invoice" onclick="return confirm('Yakin ingin menghapus invoice ini?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </td>
                                                    <?php $first = false;
                                                    endif; ?>
                                                    <td><?= $item['nama_barang'] ?></td>
                                                    <td><?= $item['quantity'] ?></td>
                                                    <td><?= $item['satuan'] ?></td>
                                                    <td><?= number_format($item['harga_beli'], 0, ',', '.') ?></td>
                                                    <td><?= number_format($jumlah_beli, 0, ',', '.') ?></td>
                                                    <td><?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                                                    <td><?= number_format($jumlah_jual, 0, ',', '.') ?></td>
                                                    <td><?= number_format($laba, 0, ',', '.') ?></td>
                                                    <td><?= number_format($persentase, 2) ?>%</td>
                                                </tr>
                                            <?php endwhile; ?>
                                         
                                            <tr style="background:#f8f9fa;font-weight:bold">
                                                <td colspan="4" class="text-end">TOTAL</td>
                                                <td><?= number_format($total_beli, 0, ',', '.') ?></td>
                                                <td></td>
                                                <td><?= number_format($total_jual, 0, ',', '.') ?></td>
                                                <td><?= number_format($total_laba, 0, ',', '.') ?></td>
                                                <td>
                                                    <?php
                                                    $persentase_total = $total_beli > 0 ? ($total_laba / $total_beli) * 100 : 0;
                                                    echo number_format($persentase_total, 2) . '%';
                                                    ?>
                                                </td>
                                            
                                                <td class="text-nowrap">
                                                    <a href="pages/invoice_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Invoice">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
                                                    <a href="pages/surat_jalan_view.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-secondary me-1" title="Surat Jalan">
                                                        <i class="fas fa-truck"></i>
                                                    </a>
                                                    <a href="index.php?page=invoice_edit&id=<?= $inv['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit Invoice">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <a href="pages/invoice_delete.php?id=<?= $inv['id'] ?>" class="btn btn-sm btn-danger" title="Hapus Invoice" onclick="return confirm('Yakin ingin menghapus invoice ini?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        $('#data').DataTable();
    });
</script>
<script>
  
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 5000);
</script>