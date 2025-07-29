<?php
include '../includes/koneksi.php';

$id = intval($_GET['id']);
$invoice = mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($invoice);

$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($data['no_invoice']) ?></title>
    <link rel="stylesheet" href="../assets/css/print.css" media="print">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="../assets/images/purnama.png" rel="icon">
    <link href="../assets/images/purnama.png" rel="apple-touch-icon">
    <link rel="icon" href="../assets/images/purnama.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/purnama.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="../assets/images/purnama.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .logo {
            width: 80px;
        }

        .header-table td {
            vertical-align: top;
        }

        .item-table th,
        .item-table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <a class="btn btn-primary" href='invoice_print.php?id=<?= $data['id'] ?>'><i class="fas fa-print"></i>
        print</a>
    <a class='btn btn-success' href="invoice_export_excel.php?id=<?= $data['id'] ?>" class="btn btn-success" target="_blank">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <hr>

    <!-- Header Perusahaan Sesuai Gambar -->
    <div style="display: flex; align-items: flex-start; margin-bottom: 10px;">
        <div style="flex-shrink: 0; margin-right: 10px;">
            <img src="../assets/images/purnama.png" alt="Logo" style="height: 60px;">
        </div>
        <div style="flex-grow: 1;">
            <div style="font-weight: bold; font-size: 18px;">PT. GANGSAR PURNAMA MANDIRI</div>
            <div>Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning Kotamadya Bekasi - 17310</div>
            <div>Telp: 021 82521962</div>
            <div>Contact person : 0852-105-39299</div>
        </div>
        <div style="text-align: right; font-size: 13px;">
            <strong>BANK ACC:</strong><br>
            KCP BEKASI RUKO D GREEN SQUARE<br>
            PT. GANGSAR PURNAMA MANDIRI<br>
            NO REK : 156-00-2000590-8<br>
            e-mail : purnama.mandiri77@gmail.com
        </div>
    </div>
    <hr>


    <!-- Tujuan -->
    <table class="no-border" style="margin-top: 20px;">
        <tr>
            <td width="50%">
                <strong>Kepada Yth :</strong><br>
                <?= nl2br($data['perusahaan']) ?><br>
                <?= nl2br($data['alamat']) ?>
            </td>
            <td class="right">
                <table class="no-border">
                    <tr>
                        <td class="bold">INVOICE</td>
                    </tr>
                    <tr>
                        <td>No: <?= $data['no_invoice'] ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal: <?= strtoupper(date('d F Y', strtotime($data['tanggal_invoice']))) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Info Kirim -->
    <table class="item-table" style="margin-top: 20px;">
        <tr>
            <th>SALES</th>
            <th>NO PO</th>
            <th>TGL PENGIRIMAN</th>
            <th>NO SURAT JALAN</th>
            <th>JATUH TEMPO</th>
        </tr>
        <tr>
            <td><?= $data['sales'] ?? 'RENI PURNAMA' ?></td>
            <td><?= $data['no_po'] ?? '-' ?></td>
            <td><?= date('d-M-y', strtotime($data['tanggal_invoice'])) ?></td>
            <td><?= $data['no_sj'] ?? '-' ?></td>
            <td><?= date('l, d F Y', strtotime($data['jatuh_tempo'])) ?></td>
        </tr>
    </table>

    <!-- Produk -->
    <table class="item-table" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>JUMLAH</th>
                <th>UNIT</th>
                <th>NAMA BARANG</th>
                <th>HARGA (Rp)</th>
                <th>JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($items)):
                $sub = $row['quantity'] * $row['harga_jual'];
                $total += $sub;
            ?>
                <tr>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['satuan'] ?></td>
                    <td><?= $row['nama_barang'] ?></td>
                    <td class="right"><?= number_format($row['harga_jual'], 2, ',', '.') ?></td>
                    <td class="right"><?= number_format($sub, 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="4" class="right">TOTAL</td>
                <td class="right"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="right">PPN</td>
                <td class="right">-</td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="right">TOTAL</td>
                <td class="right"><?= number_format($total, 2, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

</body>

</html>