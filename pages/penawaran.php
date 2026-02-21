<?php
include 'includes/koneksi.php';
$filterPerusahaan = $_POST['perusahaan'] ?? '';
$filterStatus = $_POST['status'] ?? '';

$where = [];

// Filter perusahaan (jika dipilih)
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

// Bangun query
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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Animate.css for nice fade-in effect -->
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
        <h1>Penawaran</h1>
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
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Penawaran</h5>
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

                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Filter Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">-- Semua Status --</option>
                                            <option value="menunggu" <?= (isset($_POST['status']) && $_POST['status'] == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="selesai" <?= (isset($_POST['status']) && $_POST['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                                            <option value="batal" <?= (isset($_POST['status']) && $_POST['status'] == 'batal') ? 'selected' : '' ?>>Batal</option>
                                        </select>
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
                                            <span class="text-muted">: Tombol untuk Menampilkan Surat Penawaran.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-warning me-1" title="Edit Penawaran">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Mengubah atau mengedit Surat Penawaran.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-success me-1" title="Terima Penawaran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Penawaran Goal dan ingin membuat invoice secara langsung.</span>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="btn btn-sm btn-danger" title="Tolak Penawaran">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            <span class="text-muted">: Tombol untuk Menghapus atau menolak Surat Penawaran.</span>
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
                            <h5 class="card-title">Data Penawaran</h5>
                            <p class="card-text">List Penawaran dari <b>PT. GANGSAR PURNAMA MANDIRI</b></p>
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
                                <table class="table table-striped table-sm table-hover datatable" id="dataPenawaran">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">No SP</th>
                                            <th rowspan="2">Perusahaan</th>
                                            <th rowspan="2">Penerima</th>
                                            <th rowspan="2">Tanggal</th>
                                            <th rowspan="2">Status</th>
                                            <th rowspan="2">Action</th>
                                            <th colspan="6">Detail Produk</th>
                                        </tr>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Qty</th>
                                            <th>Satuan</th>
                                            <th>Harga Jual</th>
                                            <th>Jumlah Jual</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($pnw = mysqli_fetch_assoc($dataPenawaran)) :
                                            $items = mysqli_query($konek, "SELECT * FROM penawaran_items WHERE penawaran_id = " . $pnw['id']);
                                            $rowspan = mysqli_num_rows($items);
                                            $first = true;

                                            $total_beli = 0;
                                            $total_jual = 0;
                                            $total_laba = 0;

                                            while ($item = mysqli_fetch_assoc($items)) :
                                                $jumlah_beli = $item['quantity'] * $item['harga_beli'];
                                                $jumlah_jual = $item['quantity'] * $item['harga_jual'];
                                                $laba = $jumlah_jual - $jumlah_beli;
                                                $persen = $jumlah_beli > 0 ? ($laba / $jumlah_beli) * 100 : 0;

                                                $total_beli += $jumlah_beli;
                                                $total_jual += $jumlah_jual;
                                                $total_laba += $laba;
                                        ?>
                                                <tr>
                                                    <?php if ($first): ?>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= $no++ ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= htmlspecialchars($pnw['no_sp']) ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= htmlspecialchars($pnw['nama_perusahaan']) ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= htmlspecialchars($pnw['penerima']) ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>"><?= $pnw['tanggal'] ?></td>
                                                        <td rowspan="<?= $rowspan + 1 ?>">
                                                            <?php
                                                            $stat = strtolower($pnw['status']);
                                                            $warna = '';

                                                            if ($stat == 'menunggu') {
                                                                $warna = 'background-color: #ffecd1; color: #ff8c00;'; // Orange muda
                                                            } elseif ($stat == 'selesai') {
                                                                $warna = 'background-color: #d4edda; color: #155724;'; // Hijau
                                                            } elseif ($stat == 'batal') {
                                                                $warna = 'background-color: #f8d7da; color: #721c24;'; // Merah
                                                            }
                                                            ?>
                                                            <span class="badge" style="<?= $warna ?>; padding:6px 12px; border-radius:6px;">
                                                                <?= htmlspecialchars($pnw['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-nowrap text-center" rowspan="<?= $rowspan + 1 ?>">
                                                            <!-- Tombol Lihat Penawaran -->
                                                            <a href="pages/penawaran_view.php?id=<?= $pnw['id'] ?>" class="btn btn-sm btn-info me-1" title="Lihat Surat Penawaran">
                                                                <i class="fas fa-file-alt"></i>
                                                            </a>

                                                            <?php if (strtolower($pnw['status']) === 'menunggu'): ?>
                                                                <a href="index.php?page=editPenawaran&id=<?= $pnw['id'] ?>" class="btn btn-sm btn-warning me-1" title="Edit Penawaran">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="index.php?page=acceptPenawaran&id=<?= $pnw['id'] ?>" class="btn btn-sm btn-success me-1" title="Terima Penawaran" onclick="return confirm('Terima penawaran ini?')">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </a>
                                                                <a href="pages/penawaran_decline.php?id=<?= $pnw['id'] ?>" class="btn btn-sm btn-danger" title="Tolak Penawaran" onclick="return confirm('Tolak penawaran ini?')">
                                                                    <i class="fas fa-times-circle"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>

                                                    <?php $first = false;
                                                    endif; ?>
                                                    <td><?= $item['nama_barang'] ?></td>
                                                    <td><?= $item['quantity'] ?></td>
                                                    <td><?= $item['satuan'] ?></td>
                                                    <td><?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                                                    <td><?= number_format($jumlah_jual, 0, ',', '.') ?></td>
                                                    <td><?= !empty($item['keterangan']) ? htmlspecialchars($item['keterangan']) : ' - ' ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                            <tr style="background:#f8f9fa;font-weight:bold">
                                                <td colspan="5" class="text-end">TOTAL</td>
                                                <td><?= number_format($total_jual, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div><!-- End Card -->
            </div>
        </div>
        </div>
    </section>
</main>

<!-- JS untuk datatable -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#data').DataTable();
    });
</script>
<script>
    // Auto dismiss alert setelah 5 detik
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 5000);
</script>