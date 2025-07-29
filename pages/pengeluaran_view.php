<?php
include '../includes/koneksi.php';

$id = intval($_GET['id']);

// Ambil data pengeluaran utama
$pgl = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM pengeluaran WHERE id = $id"));

// Ambil jenis pengeluaran
$jenis_query = mysqli_query($konek, "SELECT * FROM pengeluaran_jenis WHERE pengeluaran_id = $id");

// Ambil invoice terkait
$invoices = mysqli_query($konek, "
    SELECT pi.*, inv.no_invoice, inv.perusahaan 
    FROM pengeluaran_items pi
    LEFT JOIN invoices inv ON pi.invoice_id = inv.id
    WHERE pi.pengeluaran_id = $id
");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Struk Pengeluaran</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <style>
        .nota {
            max-width: 600px;
            margin: 30px auto;
            border: 1px solid #ddd;
            padding: 20px;
            font-family: 'Courier New', monospace;
        }

        .nota h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-borderless td {
            padding: 4px 8px;
        }
    </style>
</head>

<body>

    <div class="nota">
        <h4>Struk Pengeluaran</h4>

        <table class="table table-borderless">
            <tr>
                <td><strong>No Pengeluaran</strong></td>
                <td>: <?= htmlspecialchars($pgl['no_pengeluaran']) ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: <?= date('d-m-Y', strtotime($pgl['tanggal'])) ?></td>
            </tr>
            <tr>
                <td><strong>Keterangan</strong></td>
                <td>: <?= nl2br(htmlspecialchars($pgl['keterangan'])) ?></td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td>: Rp <?= number_format($pgl['total_pengeluaran'], 0, ',', '.') ?></td>
            </tr>
        </table>

        <hr>

        <h6>Jenis Pengeluaran:</h6>
        <ul>
            <?php while ($jenis = mysqli_fetch_assoc($jenis_query)): ?>
                <li><?= htmlspecialchars($jenis['jenis_pengeluaran']) ?> - Rp <?= number_format($jenis['nominal'], 0, ',', '.') ?></li>
            <?php endwhile; ?>
        </ul>

        <hr>

        <h6>Invoice Terkait:</h6>
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No Invoice</th>
                    <th>Perusahaan</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($inv = mysqli_fetch_assoc($invoices)): ?>
                    <tr>
                        <td><?= htmlspecialchars($inv['no_invoice']) ?></td>
                        <td><?= htmlspecialchars($inv['perusahaan']) ?></td>
                        <td>Rp <?= number_format($inv['nominal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a class="btn btn-primary" href='pengeluaran_print.php?id=<?= $id ?>'><i class="fas fa-print"></i>
                print</a>
            <a href="../index.php?page=pengeluaran" class="btn btn-secondary btn-sm">Kembali</a>
        </div>


    </div>

</body>

</html>